<?php 
namespace System\Admin\Controller;

use System\Core\Admin_controller;
use System\Core\View;
use System\Libs\Config;
use System\Libs\Message;
use System\Libs\Auth;
use System\Libs\DI;
use System\Libs\Validate;
use System\Libs\Session;
use System\Libs\Uploader;

class Users extends Admin_controller {

	private $email_verify = false;

	function __construct()
	{
		parent::__construct();
		$this->loadModel('user_model');
	}

	public function index()
	{
		$view = new View();

		$data['title'] = 'Users oldal';
		$data['description'] = 'Users oldal description';
        $data['users'] = $this->user_model->selectUser();

		$view->add_links(array('datatable', 'bootbox','vframework','users'));
		$view->render('users/tpl_users', $data);
	}

	
	/**
	 *	Új felhasználó hozzáadása
	 */
	public function insert()
	{
		if($this->request->has_post('submit_new_user')) {

	        // adatok a $_POST tömbből
	        $post_data = $this->request->get_post();

	        // validátor objektum létrehozása
	        $validate = new Validate();

	        // szabályok megadása az egyes mezőkhöz (mező neve, label, szabály)
	        $validate->add_rule('name', 'username', array(
	            'required' => true,
	            'min' => 2
	        ));
	        $validate->add_rule('first_name', 'userfirstname', array(
	            'required' => true,
	            'min' => 2
	        ));
	        $validate->add_rule('last_name', 'userlastname', array(
	            'required' => true,
	            'min' => 2
	        ));
	        $validate->add_rule('password', 'password', array(
	            'required' => true,
	            'min' => 6
	        ));
	        $validate->add_rule('password_again', 'password_again', array(
	            'required' => true,
	            'matches' => 'password'
	        ));
	        $validate->add_rule('email', 'email', array(
	            'required' => true,
	            'email' => true
	            // 'max' => 64
	        ));        

	        // üzenetek megadása az egyes szabályokhoz (szabály_neve, üzenet)
	        $validate->set_message('required', ':label_field_empty');
	        $validate->set_message('min', ':label_too_short');
	        $validate->set_message('matches', ':label_repeat_wrong');
	        $validate->set_message('email', ':label_does_not_fit_pattern');
	        //$validate->set_message('max', ':label_too_long');

	        // mezők validálása
	        $validate->check($post_data);

	        // HIBAELLENŐRZÉS - ha valamilyen hiba van a form adataiban
	        if(!$validate->passed()){
	            foreach ($validate->get_error() as $error_msg) {
	                Message::set('error', $error_msg);
	            }
	            $this->response->redirect('admin/users/insert');
	        }
	        else {
	        // végrehajtás, ha nincs hiba 
	            $user = array();
	            $user['name'] = $this->request->get_post('name');
	            $user['first_name'] = $this->request->get_post('first_name');
	            $user['last_name'] = $this->request->get_post('last_name');
	            $user['email'] = $this->request->get_post('email');
	            $user['phone'] = $this->request->get_post('phone');

	            if (empty($this->request->get_post('img_url'))) {
	                $user['photo'] = Config::get('user.default_photo');
	            } else {
	                $path_parts = pathinfo($this->request->get_post('img_url'));
	                $user['photo'] = $path_parts['filename'] . '.' . $path_parts['extension'];
	            }

	            $user['role_id'] = $this->request->get_post('user_group', 'integer');
	            $user['provider_type'] = ($this->request->get_uri('area') == 'admin') ? 'admin' : null;

	                // jelszó kompatibilitás library betöltése régebbi php verzió esetén
	                $this->user_model->load_password_compatibility();
	                // crypt the user's password with the PHP 5.5's password_hash() function, results in a 60 character
	                // hash string. the PASSWORD_DEFAULT constant is defined by the PHP 5.5, or if you are using PHP 5.3/5.4,
	                // by the password hashing compatibility library. the third parameter looks a little bit shitty, but that's
	                // how those PHP 5.5 functions want the parameter: as an array with, currently only used with 'cost' => XX
	                $hash_cost_factor = (Config::get('hash_cost_factor') !== null) ? Config::get('hash_cost_factor') : null;

	            $user['password_hash'] = password_hash($this->request->get_post('password'), PASSWORD_DEFAULT, array('cost' => $hash_cost_factor));

	                // ellenőrzés, hogy létezik-e már ilyen felhasználói név az adatbázisban
	                if ($this->user_model->checkUsername($user['name'])) {
	                    Message::set('error', 'username_already_taken');
	                    $this->response->redirect('admin/users/insert');
	                }

			        if(!is_null($user['email'])){
		                // ellenőrzés, hogy létezik-e már ilyen email cím az adatbázisban
		                if ($this->user_model->checkEmail($user['email'])) {
		                    Message::set('error', 'user_email_already_taken');
		                    $this->response->redirect('admin/users/insert');
		                }
			        }

	            // ha be van állítva e-mail ellenőrzéses regisztráció
	            if ($this->email_verify === true) {
	                // generate random hash for email verification (40 char string)
	                $user['activation_hash'] = sha1(uniqid(mt_rand(), true));
	                $user['active'] = 0;
	            } else {
	                $user['activation_hash'] = null;
	                $user['active'] = 1;
	            }
	            // generate integer-timestamp for saving of account-creating date
	            $user['creation_timestamp'] = time();


	            // Új felhasználó adatainak beírása az adatbázisba
	            $last_inserted_id = $this->user_model->insert($user);
	            if (!$last_inserted_id) {
	                Message::set('error', 'account_creation_failed');
	                $this->response->redirect('admin/users/insert');
	            }

	            // Ezután jön az ellenörző email küldés (ha az $email_verify tulajdonság értéke true)
	            // ha sikeres az ellenőrzés, visszatér true-val, ellenkező esetben a visszatér false-al
	            if ($this->email_verify === true) {

	                // ellenőrző email küldése, ha az ellenőrző email küldése sikertelen: felhasználó törlése az databázisból
	                if ($this->user_model->_sendVerificationEmail($last_inserted_id, $user['email'], $user['activation_hash'])) {
	                    Message::set('success', 'account_successfully_created');
	                } else {
	                    $this->user_model->delete($last_inserted_id);
	                    Message::set('error', 'verification_mail_sending_failed');
	                    $this->response->redirect('admin/users/insert');
	                }
	            }

	            // ha nincs email ellenőrzés, és minden ellenőrzés sikeres, akkor visszatér true-val
	            Message::set('success', 'user_successfully_created');
	            $this->response->redirect('admin/users');
	        }
		}

		if (Auth::hasAccess('user_insert')) {
			$view = new View();

			$data['title'] = 'Új felhasználó oldal';
			$data['description'] = 'Új felhasználó description';

			$view->add_links(array('bootstrap-fileupload','croppic','validation','user_insert'));
			$view->render('users/tpl_user_insert', $data);
		} else {
	        Message::set('error', 'Nincs engedélye felhasználót létrehozni.');
	        $this->response->redirect('admin/users');
		}

	}
	
	
	/**
	 * Felhasználó adatainak megjelenítése és módosítása
     *
	 * A metódusnak szüksége van egy user id-jére amit módosítani akarunk ($this->request->get_params('id'))
	 */
	public function profile($id)
	{
		// $id = (int)$this->request->get_params('id');	
		$id = (int)$id;	

		if($this->request->has_post('submit_edit_user')) {
            
	        // adatok a $_POST tömbből
	        $post_data = $this->request->get_post();

	        // validátor objektum létrehozása
	        $validate = new Validate();
	        // szabályok megadása az egyes mezőkhöz (mező neve, label, szabály)
	        $validate->add_rule('name', 'username', array(
	            'required' => true,
	            'min' => 2
	        ));
	        $validate->add_rule('first_name', 'userfirstname', array(
	            'required' => true,
	            'min' => 2
	        ));
	        $validate->add_rule('last_name', 'userlastname', array(
	            'required' => true,
	            'min' => 2
	        ));
	        
	        // Jelszó ellenőrzés ha üres a password és az ellenőrző password mezö
	        if (empty($this->request->get_post('password')) && empty($this->request->get_post('password_again'))) {
	            $password_empty = true;
	        } else {
	            $validate->add_rule('password', 'password', array(
	                'required' => true,
	                'min' => 6
	            ));
	            $validate->add_rule('password_again', 'password_again', array(
	                'required' => true,
	                'matches' => 'password'
	            ));
	        }

	        $validate->add_rule('email', 'email', array(
	            'required' => true,
	            'email' => true
	            // 'max' => 64
	        ));        

	        // üzenetek megadása az egyes szabályokhoz (szabály_neve, üzenet)
	        $validate->set_message('required', ':label_field_empty');
	        $validate->set_message('min', ':label_too_short');
	        $validate->set_message('matches', ':label_repeat_wrong');
	        $validate->set_message('email', ':label_does_not_fit_pattern');
	        //$validate->set_message('max', ':label_too_long');

	        // mezők validálása
	        $validate->check($post_data);

	        // HIBAELLENŐRZÉS - ha valamilyen hiba van a form adataiban
	        if(!$validate->passed()){
	            foreach ($validate->get_error() as $error_msg) {
	                Message::set('error', $error_msg);
	            }
	            $this->response->redirect('admin/users/profile/' . $id);
	        }
	        else {
	        // végrehajtás, ha nincs hiba
	        	$user = array();
	            $user['name'] = $this->request->get_post('name');
	            $user['first_name'] = $this->request->get_post('first_name');
	            $user['last_name'] = $this->request->get_post('last_name');
	            $user['phone'] = $this->request->get_post('phone');

	            //ha nem létezik a $password_empty változó, vagyis nem üres mindkét password mező	
	            if (!isset($password_empty)) {

	                // jelszó kompatibilitás library betöltése régebbi php verzió esetén
	                $this->user_model->load_password_compatibility();                
	                // crypt the user's password with the PHP 5.5's password_hash() function, results in a 60 character
	                // hash string. the PASSWORD_DEFAULT constant is defined by the PHP 5.5, or if you are using PHP 5.3/5.4,
	                // by the password hashing compatibility library. the third parameter looks a little bit shitty, but that's
	                // how those PHP 5.5 functions want the parameter: as an array with, currently only used with 'cost' => XX
	                $hash_cost_factor = (Config::get('hash_cost_factor') !== null) ? Config::get('hash_cost_factor') : null;
	                $user['password_hash'] = password_hash($this->request->get_post('password'), PASSWORD_DEFAULT, array('cost' => $hash_cost_factor));
	            }

	            $user['email'] = $this->request->get_post('email');

	            if ($this->request->has_post('user_group')) {
	                $user['role_id'] = $this->request->get_post('user_group', 'integer');
	            }

	            //ha van feltöltve user kép
	            if (!empty($this->request->get_post('img_url'))) {
	                $path_parts = pathinfo($this->request->get_post('img_url'));
	                $user['photo'] = $path_parts['filename'] . '.' . $path_parts['extension'];
	            }

	            // Megvizsgáljuk, hogy van-e már ilyen nevű user (de nem az amit módosítani akarunk)
	            if ($this->user_model->checkUserNoLoggedIn($id, $user['name'])) {
	                Message::set('error', 'username_already_taken');
	                $this->response->redirect('admin/user/profile/' . $id);
	            }

	  		/*
	              if(!is_null($user['email'])){
	              // Megvizsgáljuk, hogy van-e már ilyen email cím (de nem az amit módosítani akarunk)

		            // ha már van ilyen email cím
	              	if ($this->user_model->checkEmailNoLoggedIn($id, $user['email'])) {
						Message::set('error', 'user_email_already_taken');
	                	$this->response->redirect('admin/user/profile');
	              	}
	              }
	        */   

	            // új adatok beírása az adatbázisba (update) a $user tömb tartalmazza a frissítendő adatokat 
	            $result = $this->user_model->update($id, $user);

	            if ($result !== false) {
	                // ha a bejelentkezett user adatait módosítjuk, akkor a session adatokat is frissíteni kell
	                if (Session::get('user_data.id') == $id) {
	                    // Módosítjuk a $_SESSION tömben is a user adatait!
	                    Session::set('user_data.name', $user['name']);
	                    Session::set('user_data.email', $user['email']);
	                    if (isset($user['role_id'])) {
	                        Session::set('user_data.role_id', $user['role_id']);
	                    }
	                    if (isset($user['user_photo'])) {
	                        Session::set('user_data.photo', $user['photo']);
	                    }
	                }
	                Message::set('success', 'user_data_update_success');
	                $this->response->redirect('admin/users');
	            } else {
	                Message::set('error', 'unknown_error');
	                $this->response->redirect('admin/users/profile/' . $id);
	            }
	        } 
		}
		$view = new View();

		$data['title'] = 'Profilom oldal';
		$data['description'] = 'Profilom description';
		$data['user'] = $this->user_model->selectUser($id);

		$view->add_links(array('bootstrap-fileupload', 'croppic', 'validation', 'user_profile'));
		$view->render('users/tpl_profile', $data);
	}
	
	
    public function user_roles()
    {
    	$view = new View();

        $data['title'] = 'Felhasználói csoportok oldal';
        $data['description'] = 'Felhasználói csoportok description';
        $data['roles'] = DI::get('auth')->getRoles();
        $data['roles_counter'] = $this->user_model->rolesCounter();

        $view->add_link('js', ADMIN_JS . 'pages/common.js');
        $view->render('users/tpl_user_roles', $data);
    }
	
	/**
	 * Felhasználói csoport engedélyeinek módosítása
	 */
 	public function edit_roles($id)
 	{
        // $role_id = (int)$this->request->get_params('id');
        $role_id = (int)$id;
        
        if ($this->request->has_post('submit_edit_roles')) {

        	$permissions = $this->request->get_post();
			unset($permissions['submit_edit_roles']);

			DI::get('auth')->savePerms($role_id, $permissions);
    	   	Message::set('success', 'Módosítások elmentve!');
    	   	$this->response->redirect('admin/users/edit_roles/' . $role_id);
        }

        // csak akkor lehet szerkeszteni a jogokat, ha nem szuperadmint akarunk szerkeszteni, tehát az $id nem 1
        // és csak szuperadmin szerkeszthet
        if ($id != 1 && Auth::isSuperadmin()) {
        
			$view = new View();

	        $data['title'] = 'Felhasználói jogosultságok szerkesztése oldal';
	        $data['description'] = 'Felhasználói jogosultságok szerkesztése description';
			
			$auth = DI::get('auth');
			// összes permissiont	
			$data['permissions'] = $auth->getAllPerms();
			// a $role_id-hez tartozó szerep adatai
			$data['role'] = $auth->getRoles($role_id);
				if (empty($data['role'])) {
        			Message::set('error', 'A felhasználói csoport nem létezik.');
					$this->response->redirect('admin/users/user_roles');
				}

			// a $role_id-hez tartozó szerep engedélyei
			$data['allowed_permissions'] = $auth->getRolePerms($role_id);
			
			$view->add_link('js', ADMIN_JS . 'pages/common.js');
	        $view->render('users/tpl_edit_roles', $data);
        } else{
        	Message::set('error', 'A művelet nem engedélyezett.');
        	$this->response->redirect('admin/users/user_roles');
        }
    }
	
	/**
	 *	User törlése AJAX-al
	 */
	public function delete()
	{
        if($this->request->is_ajax()){
	        if(Auth::hasAccess('user_delete')){
	        	// a POST-ban kapott item_id egy tömb
	        	$id_arr = $this->request->get_post('item_id');
		        // a sikeres törlések számát tárolja
		        $success_counter = 0;
		        // a sikeresen törölt id-ket tartalmazó tömb
		        $success_id = array();
		        // a sikertelen törlések számát tárolja
		        $fail_counter = 0;

		        $file_helper = DI::get('file_helper'); 
		        $photo_path = Config::get('user.upload_path');
		        $default_photo = Config::get('user.default_photo');

		        // bejárjuk a $id_arr tömböt és minden elemen végrehajtjuk a törlést
		        foreach ($id_arr as $id) {
		            //átalakítjuk a integer-ré a kapott adatot
		            $id = (int) $id;
		            //lekérdezzük a törlendő user avatar képének a nevét, hogy törölhessük a szerverről
		            $photo_name = $this->user_model->selectPicture($id);
		            //felhasználó törlése 
		            $result = $this->user_model->delete($id);

		            if ($result !== false) {
		                // ha a törlési sql parancsban nincs hiba
		                if ($result > 0) {
		                    //kép törlése, ha nem a default kép
		                    if ($photo_name != $default_photo) {
		                        //kép file törlése a szerverről
		                        $file_helper->delete($photo_path . $photo_name);    
		                    }
		                    //sikeres törlés
		                    $success_counter += $result;
		                    $success_id[] = $id;
		                } else {
		                    //sikertelen törlés
		                    $fail_counter += 1;
		                }
		            } else {
		                // ha a törlési sql parancsban hiba van
		                $this->response->json(array(
		                    'status' => 'error',                  
		                    'message_error' => 'unknown_error',                  
		                ));
		            }
		        }

		        // üzenetek visszaadása
		        $respond = array();
		        $respond['status'] = 'success';
		        
		        if ($success_counter > 0) {
		            $respond['message_success'] = $success_counter . ' felhasználó törölve.';
		        }
		        if ($fail_counter > 0) {
		            $respond['message_error'] = $fail_counter . ' felhasználót már töröltek!';
		        }

		        // respond tömb visszaadása
		        $this->response->json($respond);


	        } else {
	            $this->response->json(array(
	            	'status' => 'error',
	            	'message' => 'Nincs engedélye a művelet végrehajtásához!'
	            ));
	        }
        }
	}

    /**
	 *	A felhasználó képét tölti fel a szerverre, és készít egy kisebb méretű képet is.
	 *
	 *	Ez a metódus kettő XHR kérést dolgoz fel.
	 *	Meghívásakor kap egy id nevű paramétert melynek értékei upload vagy crop
	 *		upload paraméterrel meghívva: feltölti a kiválasztott képet
	 *		crop paraméterrel meghívva: megvágja az eredeti képet és feltölti	
	 *	(a paraméterek megadása a new_user.js fájlban található: admin/users/user_img_upload/upload vagy admin/user_img_upload/crop)
	 *
	 *	Az user_img_upload() model metódus JSON adatot ad vissza (ezt "echo-za" vissza ez a metódus a kérelmező javascriptnek). 
	 */
	public function user_img_upload()
	{
		if( $this->request->is_ajax() ){
            // feltöltés helye
            $upload_path = Config::get('user.upload_path');

            // Kiválasztott kép feltöltése
            if ($this->request->has_params('upload')) {

                //képkezelő objektum létrehozása (a kép a szerveren a tmp könyvtárba kerül)	
                $image = new Uploader($this->request->getFiles('img'));
                $tempfilename = 'temp_' . uniqid();
                $image->allowed(array('image/*'));
    			$image->resize(Config::get('user.width', 600), null);
				$image->save($upload_path, $tempfilename);
					
				if ($image->checkError()) {
                    $this->response->json(array(
                        "status" => 'error',
                        "message" => $image->getError()
                    ));
				} else {
                    $this->response->json(array(
                        "status" => 'success',
                        "url" => $upload_path . $image->getDest('filename'),
                        "width" => $image->getDest('width'),
                        "height" => $image->getDest('height')
                    ));
				}
            }

            // Kiválasztott kép vágása és vágott kép feltöltése
            else if ($this->request->has_params('crop')) {

                // a croppic js küldi ezeket a POST adatokat 	
                $imgUrl = $this->request->get_post('imgUrl');
                // original sizes
                $imgInitW = $this->request->get_post('imgInitW');
                $imgInitH = $this->request->get_post('imgInitH');
                // resized sizes
                //kerekítjük az értéket, mert lebegőpotos számot is kaphatunk és ez hibát okozna a kép generálásakor
                $imgW = round($this->request->get_post('imgW'));
                $imgH = round($this->request->get_post('imgH'));
                // offsets
                // megadja, hogy mennyit kell vágni a kép felső oldalából
                $imgY1 = $this->request->get_post('imgY1');
                // megadja, hogy mennyit kell vágni a kép bal oldalából
                $imgX1 = $this->request->get_post('imgX1');
                // crop box
                $cropW = $this->request->get_post('cropW');
                $cropH = $this->request->get_post('cropH');
                // rotation angle
                //$angle = $this->request->get_post('rotation');
                //a $right_crop megadja, hogy mennyit kell vágni a kép jobb oldalából
                $right_crop = ($imgW - $imgX1) - $cropW;
                //a $bottom_crop megadja, hogy mennyit kell vágni a kép aljából
                $bottom_crop = ($imgH - $imgY1) - $cropH;

                //képkezelő objektum létrehozása (a feltöltött kép elérése a paraméter)	
                $image = new Uploader($imgUrl);
                $newfilename = 'user_' . md5(uniqid());
    			$image->resize($imgW, null);
    			$image->crop(array($imgY1, $right_crop, $bottom_crop, $imgX1));
				$image->save($upload_path, $newfilename);

				// hibaellenőrzés
                if ($image->checkError()) {
                    $this->response->json(array(
                        "status" => 'error',
                        "message" => $image->getError()
                    ));                    
                } else {
                	// temp kép törlése
                	DI::get('file_helper')->delete($imgUrl);

                    $this->response->json(array(
                        "status" => 'success',
                        "url" => $upload_path . $image->getDest('filename')
                    ));
                }

            }
		}
	}

    /**
     * (AJAX) Az users táblában módosítja a user_active mező értékét
     *
     * @return void
     */
    public function change_status()
    {
        if ( $this->request->is_ajax() ) {
        	// jogosultság vizsgálat
        	if (Auth::hasAccess('user_changestatus')) {
        	
	            if ( $this->request->has_post('action') && $this->request->has_post('id') ) {
				
					$id = $this->request->get_post('id', 'integer');
					$action = $this->request->get_post('action');

					if($action == 'make_active') {
						$result = $this->user_model->changeStatus($id, 1);
						if($result !== false){
							$this->response->json(array(
								"status" => 'success',
								"message" => 'A felhasználó aktiválása megtörtént!'
							)); 	
						} else {
							$this->response->json(array(
								"status" => 'error',
								"message" => 'Adatbázis hiba! A felhasználó státusza nem változott meg!'
							));
						}
					}
					if($action == 'make_inactive') {
						//ha a szuperadmint akarjuk blokkolni 
						if($this->user_model->is_user_superadmin($id)) {
							$this->response->json(array(
								"status" => 'error',
								"message" => 'Szuperadminisztrátor nem blokkolható!'
							));
							return;					
						}
					
						$result = $this->user_model->changeStatus($id, 0);
						if($result !== false){
							$this->response->json(array(
								"status" => 'success',
								"message" => 'A felhasználó blokkolása megtörtént!'
							)); 	
						} else {
							$this->response->json(array(
								"status" => 'error',
								"message" => 'Adatbázis hiba! A felhasználó státusza nem változott meg!'
							));
						}
						
					}
				} else {
					$this->response->json(array(
						"status" => 'error',
						"message" => 'unknown_error'
					));
				}

			} else {
				$this->response->json(array(
					"status" => 'error',
					"message" => 'Nincs engedélye a művelet végrehajtásához.'
				));
			}

		} else {
			$this->response->redirect('admin/error');
		}
    }	

	/**
	 *	Új jelszó küldése a felhasználónak (elfelejtett jelszó esetén)
     *  - lekérdezi, hogy van-e a $_POST-ban kapott email címmel rendelkező felhasználó
     *  - generál egy 8 karakter hosszú jelszót és egy new_password_hash-t
     *  - az új password hash-t az adatbázisba írja
     *  - elküldi email-ben az új jelszót a felhasználónak
     *  - ha az email küldése sikertelen, visszaírja az adatbázisba a régi password hash-t
	 */
	public function forgottenpw_AJAX()
	{
		if($this->request->is_ajax()){
            
            // a felhasználó email címe, amire küldjük az új jelszót
            $to_email = $this->request->get_post('user_email');
            
            // lekérdezzük, hogy ehhez az email címhez tartozik-e user (lekérdezzük a nevet, és a password hash-t)
            $result = $this->user_model->getPasswordHash($to_email);
                // ha nincsen ilyen e-mail címmel regisztrált felhasználó 
                if(empty($result)){
                    $message = array(
                      'status' => 'error',
                      'message' => 'Nincsen ilyen e-mail címmel regisztrált felhasználó!'
                    );
                    echo json_encode($message);
                    exit();                
                }
            
            $to_name = $result[0]['name'];
            $old_pw = $result[0]['password_hash'];
                  
                // 8 karakter hosszú új jelszó generálása (str_shuffle összekeveri a stringet, substr levágja az első 8 karaktert)
                $new_password = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8);
                $hash_cost_factor = (Config::get('hash_cost_factor') !== null) ? Config::get('hash_cost_factor') : null;
                $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT, array('cost' => $hash_cost_factor));            
            
            // új jelszó hash beírása az adatbázisba
            $result = $this->user_model->setNewPassword($to_email, $new_password_hash);
                // ha hiba történt a adatbázisba íráskor
                if($result === false){
                    $message = array(
                        'status' => 'error',
                        'message' => 'Adatbázis hiba!'
                    );
                    echo json_encode($message);
                    exit();    
                }
            
            


// email küldés !!!!!!!!!!

// settings adatok lekérdezése az adatbázisból
$data = $this->user_model->get_settings();            
            
            $from_email = $data['email'];
            $from_name = $data['ceg'];

            $subject = "Üzenet érkezett a {$from_name} weblaptól";
            $msg = <<<_msg

            <html>    
            <body>
                <h2>Új jelszó</h2>
                <div>
                    <p>
                        Az ön új jelszava a {$from_name} weblaphoz.
                    </p>
                    <p>
                        <strong>Az ön új jelszava: </strong> {$new_password}
                    </p>
                </div> 
            </body>
            </html>    
_msg;
            
            $result = $this->user_model->send_email($from_email, $from_name, $subject, $msg, $to_email, $to_name);






            if ($result) {
                $message = array(
                  'status' => 'success',
                  'message' => 'Új jelszó elküldve!'
                );
                echo json_encode($message);
                exit();
            } else {
                // régi password hash visszaírása az adatbázisba
                $this->user_model->setNewPassword($to_email, $old_pw);
                
                $message = array(
                  'status' => 'error',
                  'message' => 'Az új jelszó küldése sikertelen!'
                );
                echo json_encode($message);
                exit();
            }

		} else {
			$this->response->redirect('admin/error');	
		}
		
	}   


}
?>