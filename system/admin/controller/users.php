<?php 
namespace System\Admin\Controller;
use System\Core\Admin_controller;
use System\Core\View;
use System\Libs\Util;
use System\Libs\Auth;

class Users extends Admin_controller {

	function __construct()
	{
		parent::__construct();
		$this->loadModel('user_model');
	}

	public function index()
	{
		$view = new View();

		$view->title = 'Users oldal';
		$view->description = 'Users oldal description';
		
		$view->add_links(array('datatable', 'bootbox','vframework','users'));

        // userek adatainak lekérdezése
        $view->all_user = $this->user_model->user_data_query();	
//$this->view->debug(true);	
        $view->set_layout('tpl_layout');
		$view->render('users/tpl_users');
	}

	
	/**
	 *	Új felhasználó hozzáadása
	 */
	public function insert()
	{
		// új user hozzáadása
		if($this->request->has_post('submit_new_user')) {

			$result = $this->user_model->insert_user();
			
			if($result) {
				Util::redirect('users');
			}
			else {
				Util::redirect('users/insert');
			}
		}

		$view = new View();

		$view->title = 'Új felhasználó oldal';
		$view->description = 'Új felhasználó description';

		$view->add_links(array('bootstrap-fileupload','croppic','validation','user_insert'));
		
		$view->set_layout('tpl_layout');
		$view->render('users/tpl_user_insert');
	}
	
	
	/**
	 * Felhasználó adatainak megjelenítése és módosítása
     *
	 * A metódusnak szüksége van egy user id-jére amit módosítani akarunk ($this->request->get_params('id'))
	 */
	public function profile()
	{
		$id = (int)$this->request->get_params('id');	

		if($this->request->has_post('submit_edit_user')) {
            
			$result = $this->user_model->update_user((int)$id);
			
			if($result) {
				Util::redirect('users');
			}
			else {
				Util::redirect('users/profile/' . $id);
			}
		}
		
		$view = new View();

		$view->title = 'Profilom oldal';
		$view->description = 'Profilom description';
		
		$view->add_links(array('bootstrap-fileupload', 'croppic', 'validation', 'user_profile'));
        
		// visszadja a bejelentkezett user adatait egy tömbbe (id, név, telefon, password... stb.)
		$view->data_arr = $this->user_model->user_data_query($id);

		$view->set_layout('tpl_layout');
		$view->render('users/tpl_profile');
	}
	
	
    public function user_roles()
    {
    	$view = new View();

        $view->title = 'Felhasználói csoportok oldal';
        $view->description = 'Felhasználói csoportok description';
        
        $view->add_link('js', ADMIN_JS . 'pages/common.js');

        $view->roles = Auth::instance()->getRoles();
        $view->roles_counter = $this->user_model->rolesCounter();

		$view->set_layout('tpl_layout');
        $view->render('users/tpl_user_roles');
    }
	
	
 	public function edit_roles()
 	{
        $role_id = (int)$this->request->get_params('id');
        
        if ($this->request->has_post('submit_edit_roles')) {

        	$permissions = $this->request->get_post();
			unset($permissions['submit_edit_roles']);

			Auth::instance()->savePerms($role_id, $permissions);
    	   	Message::set('success', 'Módosítások elmentve!');

            Util::redirect('users/edit_roles/' . $role_id);
        }

		$view = new View();

        $view->title = 'Felhasználói jogosultságok szerkesztése oldal';
        $view->description = 'Felhasználói jogosultságok szerkesztése description';
        
		$view->add_link('js', ADMIN_JS . 'pages/common.js');


		$auth = Auth::instance();
		// összes permission adata	
		$view->role_permissions = $auth->getAllPerms();
		// a $role_id-hez tartozó szerep adatai és engedélyei
		$view->role =  $auth->getRoles($role_id);

		$view->set_layout('tpl_layout');
        $view->render('users/tpl_edit_roles');
    }
	
	/**
	 *	User törlése AJAX-al
	 */
	public function delete_user_AJAX()
	{
        if($this->request->is_ajax()){
	        if(Auth::hasAccess('delete_user')){
	        	// a POST-ban kapott user_id egy string ami egy szám vagy számok felsorolása pl.: "23" vagy "12,45,76" 
	        	$id = $this->request->get_post('item_id');
            	$respond = $this->user_model->delete_user_AJAX($id);
        		echo json_encode($respond);
	        } else {
	            echo json_encode(array(
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
			echo $this->user_model->user_img_upload();
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
            if ( $this->request->has_post('action') && $this->request->has_post('id') ) {
			
				$id = $this->request->get_post('id', 'integer');
				$action = $this->request->get_post('action');

				if($action == 'make_active') {
					$result = $this->user_model->change_status_query($id, 1);
					if($result){
						echo json_encode(array(
							"status" => 'success',
							"message" => 'A felhasználó aktiválása megtörtént!'
						)); 	
					} else {
						echo json_encode(array(
							"status" => 'error',
							"message" => 'Adatbázis hiba! A felhasználó státusza nem változott meg!'
						));
					}
				}
				if($action == 'make_inactive') {
					//ha a szuperadmint akarjuk blokkolni 
					if($this->user_model->is_user_superadmin($id)) {
						echo json_encode(array(
							"status" => 'error',
							"message" => 'Szuperadminisztrátor nem blokkolható!'
						));
						return;					
					}
				
					$result = $this->user_model->change_status_query($id, 0);
					if($result){
						echo json_encode(array(
							"status" => 'success',
							"message" => 'A felhasználó blokkolása megtörtént!'
						)); 	
					} else {
						echo json_encode(array(
							"status" => 'error',
							"message" => 'Adatbázis hiba! A felhasználó státusza nem változott meg!'
						));
					}
					
				}
			} else {
				echo json_encode(array(
					"status" => 'error',
					"message" => 'Adatbázis lekérdezési hiba!'
				));
			}
		} else {
			Util::redirect('error');
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
            
            $to_name = $result[0]['user_name'];
            $old_pw = $result[0]['user_password_hash'];
                  
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
			Util::redirect('error');
		}
		
	}   


}
?>