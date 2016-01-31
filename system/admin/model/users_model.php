<?php 
class Users_model extends Admin_model {

	/**
	 *	Legyen-e email visszaigazolós regisztráció
	 *	Értéke: true vagy false
	 */
	private $email_verify;

	/**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
	function __construct()
	{
		parent::__construct();
		
		//regisztráció email-es ellenőrzésének be- vagy kikapcsolása
		//$this->email_verify = Config::get('reg_email_verify', true);
		$this->email_verify = false;
	}
	
    /*
     * Felhsználók adatainak lekérdezése
     */
	public function all_user()
	{
		// a query tulajdonság ($this->query) tartalmazza a query objektumot
		$this->query->reset();
        $this->query->set_table(array('users')); 
		$this->query->set_columns(array(
            'users.user_id',
            'users.user_name',
            'users.user_email',
            'users.user_active',
            'users.user_role_id',
            'users.user_first_name',
            'users.user_last_name',
            'users.user_phone',
            'users.user_photo',
            'roles.role_name'
        )); 
		$this->query->set_join('left', 'roles', 'users.user_role_id', '=', 'roles.role_id'); 
		return $this->query->select(); 
	}
	
	public function new_user()
	{
		$error_counter = 0;
	
    // User név ellenőrzés
		if (empty($_POST['name'])) {
			Message::set('error', 'username_field_empty');
            $error_counter += 1;
        }
    /*
		if (strlen($_POST['name']) > 64 OR strlen($_POST['name']) < 2) {
            Message::set('error', 'username_too_short_or_too_long');
			$error_counter += 1;
        }
		if (!preg_match('/^[\_\sa-záöőüűóúéíÁÖŐÜŰÓÚÉÍ\d]{2,64}$/i', $_POST['name'])) {
			Message::set('error', 'username_does_not_fit_pattern');
            $error_counter += 1;
        }
    */
		
	// Vezetéknév ellenőrzés	
		if(empty($_POST['first_name'])) {
			Message::set('error', 'userfirstname_field_empty');
            $error_counter += 1;
		}
    /*    
		if (strlen($_POST['first_name']) > 64 OR strlen($_POST['first_name']) < 2) {
			Message::set('error', 'userfirstname_too_short_or_too_long');
            $error_counter += 1;
		}
		if (!preg_match('/^[\_\sa-záöőüűóúéíÁÖŐÜŰÓÚÉÍ]{2,64}$/i', $_POST['first_name'])) {
			Message::set('error', 'userfirstname_does_not_fit_pattern');
            $error_counter += 1;
		}
    */    
			
	// Utónév ellenőrzés
		if(empty($_POST['last_name'])) {
			Message::set('error', 'userlastname_field_empty');
            $error_counter += 1;
		}
    /*    
		if (strlen($_POST['last_name']) > 64 OR strlen($_POST['last_name']) < 2) {
			Message::set('error', 'userlastname_too_short_or_too_long');
            $error_counter += 1;
		}
		if (!preg_match('/^[\_\sa-záöőüűóúéíÁÖŐÜŰÓÚÉÍ]{2,64}$/i', $_POST['last_name'])) {
			Message::set('error', 'userlastname_does_not_fit_pattern');
            $error_counter += 1;		
		}
			
	// Telefonszám ellenőrzés

		if(empty($_POST['phone'])){
			Message::set('error', 'userphone_field_empty');
            $error_counter += 1;
		}
		if (!preg_match('~^(36)[\s-]?([0-9]{1,2})[\s-]?([0-9]{3})[\s-]?([0-9]{4})$~', $_POST['phone'])) {
			Message::set('error', 'userphone_does_not_fit_pattern');
            $error_counter += 1;
		}
    */    
		
	// Jelszó ellenőrzés
		if (empty($_POST['password']) OR empty($_POST['password_again'])) {
			Message::set('error', 'password_field_empty');
            $error_counter += 1;
        }
		if (strlen($_POST['password']) < 6) {
			Message::set('error', 'password_too_short');
            $error_counter += 1;
        }
		if ($_POST['password'] !== $_POST['password_again']) {
			Message::set('error', 'password_repeat_wrong');
            $error_counter += 1;
        }

	// E-mail ellenőrzés
		if (empty($_POST['email'])) {
			Message::set('error', 'email_field_empty');
            $error_counter += 1;
        }
    /*    
		if (strlen($_POST['email']) > 64) {
			Message::set('error', 'email_too_long');
            $error_counter += 1;
        }
		if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			Message::set('error', 'email_does_not_fit_pattern');
            $error_counter += 1;
        }
    */    
		
	// végrehajtás, ha nincs hiba	
		if ($error_counter == 0) {
			
            // clean the input
            //$user_name = htmlentities($_POST['name'], ENT_QUOTES, "UTF-8");
            $user_name = $this->request->get_post('name');
            $first_name = $this->request->get_post('first_name');
            $last_name = $this->request->get_post('last_name');
            
			if( !empty( $this->request->get_post('email') ) ){
				$user_email = htmlentities($this->request->get_post('email'), ENT_QUOTES, "UTF-8");
			} else {
				$user_email = null;
			}
			
			if( empty($this->request->get_post('img_url')) ){
				$img_url = Config::get('user.default_photo');
			} else {
				$path_parts = pathinfo( $this->request->get_post('img_url') );
				$img_url = htmlentities($path_parts['filename'] . '.' . $path_parts['extension'], ENT_QUOTES, "UTF-8");
			}

            $user_group = $this->request->get_post('user_group', 'integer');
            
            // crypt the user's password with the PHP 5.5's password_hash() function, results in a 60 character
            // hash string. the PASSWORD_DEFAULT constant is defined by the PHP 5.5, or if you are using PHP 5.3/5.4,
            // by the password hashing compatibility library. the third parameter looks a little bit shitty, but that's
            // how those PHP 5.5 functions want the parameter: as an array with, currently only used with 'cost' => XX
            $hash_cost_factor = (Config::get('hash_cost_factor') !== null) ? Config::get('hash_cost_factor') : null;
			
			$user_password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT, array('cost' => $hash_cost_factor));

            // check if username already exists
            $query = $this->connect->prepare("SELECT * FROM users WHERE user_name = :user_name");
            $query->execute(array(':user_name' => $user_name));
            $count =  $query->rowCount();
            if ($count == 1) {
                Message::set('error', 'username_already_taken');
                return false;
            }

/*
			if(!is_null($user_email)){
				// check if email already exists
				$query = $this->connect->prepare("SELECT user_id FROM users WHERE user_email = :user_email");
				$query->execute(array(':user_email' => $user_email));
				$count =  $query->rowCount();
				if ($count == 1) {
					Message::set('error', 'user_email_already_taken');
					return false;
				}
			}	
*/
			

			// ha be van állítva e-mail ellenőrzéses regisztráció
			if($this->email_verify === true) {
				// generate random hash for email verification (40 char string)
				$user_activation_hash = sha1(uniqid(mt_rand(), true));
				$user_active = 0;
			} else {
				$user_activation_hash = null;
				$user_active = 1;
			}
            // generate integer-timestamp for saving of account-creating date
            $user_creation_timestamp = time();

            // write new users data into database
            $sql = "INSERT INTO users (user_name, user_first_name, user_last_name, user_phone, user_password_hash, user_email, user_active, user_role_id, user_photo, user_creation_timestamp, user_activation_hash, user_provider_type)
                    VALUES (:user_name, :user_first_name, :user_last_name, :user_phone, :user_password_hash, :user_email, :user_active, :user_role_id, :user_photo, :user_creation_timestamp, :user_activation_hash, :user_provider_type)";

			$query = $this->connect->prepare($sql);
      			
			$query->execute(array(':user_name' => $user_name,
                                  ':user_first_name' => $first_name,
                                  ':user_last_name' => $last_name,
                                  ':user_phone' => $_POST['phone'],
                                  ':user_password_hash' => $user_password_hash,
                                  ':user_email' => $user_email,
                                  ':user_active' => $user_active,
                                  ':user_role_id' => $user_group,
                                  ':user_photo' => $img_url,
                                  ':user_creation_timestamp' => $user_creation_timestamp,
                                  ':user_activation_hash' => $user_activation_hash,
                                  ':user_provider_type' => 'DEFAULT'
								  ));
            $count =  $query->rowCount();
            if ($count != 1) {
                Message::set('error', 'account_creation_failed');
                return false;
            }
	
            // get user_id of the user that has been created, to keep things clean we DON'T use lastInsertId() here
            $query = $this->connect->prepare("SELECT user_id FROM users WHERE user_name = :user_name");
            $query->execute(array(':user_name' => $user_name));
            if ($query->rowCount() != 1) {
                Message::set('error', 'unknown_error');
                return false;
            }
		
			
			// Ezután jön az ellenörző email küldés (ha az $email_verify tulajdonság értéke true)
			// ha sikeres az ellenőrzés, visszatér true-val, ellenkező esetben a visszatér false-al
			if($this->email_verify === true) {
				$result_user_row = $query->fetch(PDO::FETCH_OBJ);
				$user_id = $result_user_row->user_id;
				
				// send verification email, if verification email sending failed: instantly delete the user
				if ($this->sendVerificationEmail($user_id, $user_email, $user_activation_hash)) {
					Message::set('success', 'account_successfully_created');
                    return true;
				} else {
					$query = $this->connect->prepare("DELETE FROM users WHERE user_id = :last_inserted_id");
					$query->execute(array(':last_inserted_id' => $user_id));
					Message::set('error', 'verification_mail_sending_failed');
                    return false;
				}
			}
			
		// ha nincs email ellenőrzés, és minden ellenőrzés sikeres, akkor visszatér true-val
		Message::set('success', 'user_successfully_created');
        return true;
			
        } else {
			// ha valamilyen hiba volt a form adataiban
			return false;
        }
//            Message::set('error', 'unknown_error');
    }
    
	/**
	 *	Admin user törlése
	 *
	 *	@return integer or false
	 */
	public function delete_user()
	{
		// a sikeres törlések számát tárolja
		$success_counter = 0;
		// a sikertelen törlések számát tárolja
		$fail_counter = 0; 
		
		// Több user törlése
		if( $this->request->has_post('delete_user') ) {
				
			$data_arr = $this->request->get_post();

			//eltávolítjuk a tömbből a felesleges elemeket	
			if(isset($data_arr['delete_user'])) {
				unset($data_arr['delete_user']);
			}
			if(isset($data_arr['users_length'])) {
				unset($data_arr['users_length']);
			}
		} else {
		// egy user törlése (nem POST adatok alapján)
			if( !$this->request->has_params('id') ){
				throw new Exception('Nincs id-t tartalmazo parameter az url-ben (ezert nem tudunk torolni id alapjan)!');
				return false;
			}
			//berakjuk a $data_arr tömbbe a törlendő felhasználó id-jét
			$data_arr = array($this->request->get_params('id'));
		}

		// bejárjuk a $data_arr tömböt és minden elemen végrehajtjuk a törlést
		foreach($data_arr as $value) {
			//átalakítjuk a integer-ré a kapott adatot
			$value = (int)$value;
			
                // lekérdezzük, hogy kapcsolódik-e hozzá bevitt munka
                $this->query->reset();
                $this->query->set_table('jobs');
                $this->query->set_columns('COUNT(*)');
                $this->query->set_where('job_ref_id', '=', $value);
                $job_number = $this->query->select();

                //ha nem 0 a visszadott érték, akkor van munkája a usernek és nem törölhető
                if($job_number[0]['COUNT(*)'] != '0') {
                    Message::set('error', 'A felhasználó nem törölhető, mert kapcsolódik hozzá munka.'); 
                    continue;
                }
            
            
				//lekérdezzük a törlendő user avatar képének a nevét, hogy törölhessük a szerverről
				$this->query->reset();
				$this->query->set_table('users');
				$this->query->set_columns(array('user_photo'));
				$this->query->set_where('user_id', '=', $value);
				$photo_name = $this->query->select();
            
            
			//felhasználó törlése	
			$this->query->reset();
			$this->query->set_table(array('users'));
			//a delete() metódus integert (lehet 0 is) vagy false-ot ad vissza
			$result = $this->query->delete('user_id', '=', $value);
			
			if($result !== false) {
				// ha a törlési sql parancsban nincs hiba
				if($result > 0){
					//kép törlése, ha nem a default kép
					if($photo_name[0]['user_photo'] != Config::get('user.default_photo')){
						//kép file törlése a szerverről (ha az Util::del_file() falsot ad vissza nem tudtuk törölni a képet... hibaüzenet)
						$path = Config::get('user.upload_path');
						if(!Util::del_file($path . $photo_name[0]['user_photo'])){
                            Message::log('user_photo_can_not_be_deleted');    
                        }
					}
					//sikeres törlés
					$success_counter += $result;
				}
				else {
					//sikertelen törlés
					$fail_counter += 1;
				}
				//continue;
			}
			else {
				// ha a törlési sql parancsban hiba van
				throw new Exception('Hibas sql parancs: nem sikerult a DELETE lekerdezes az adatbazisbol!');
				return false;
			}
		}

		// üzenetek eltárolása
		if($success_counter > 0) {
		    Message::set('success', $success_counter . ' felhasználó törölve.');    
		}
		if($fail_counter > 0){
		    Message::set('error', $fail_counter . ' felhasználót nem sikerült törölni!');    
        }
		
		// default visszatérési érték (akkor tér vissza false-al ha hibás az sql parancs)	
		return true;			
	}
	
	/**
	 *	Felhasználó adatainak módosítása
     *
	 * @param  integer $user_id
	 */
	public function edit_user($user_id)
	{
			$error_counter = 0;
	
		// User név ellenőrzés
			if ( empty($this->request->get_post('name')) ) {
				Message::set('error', 'username_field_empty');
                $error_counter += 1;
			}
        /*
			if (strlen($_POST['name']) > 64 OR strlen($_POST['name']) < 2) {
				Message::set('error', 'username_too_short_or_too_long');
                $error_counter += 1;
			}
			if (!preg_match('/^[\_a-záöőüűóúéíÁÖŐÜŰÓÚÉÍ\d]{2,64}$/i', $_POST['name'])) {
				Message::set('error', 'username_does_not_fit_pattern');
                $error_counter += 1;
			}
        */    
			
		// Vezetéknév ellenőrzés	
			if( empty($this->request->get_post('first_name')) ) {
				Message::set('error', 'userfirstname_field_empty');
                $error_counter += 1;
			}
        /*
			if (strlen($_POST['first_name']) > 64 OR strlen($_POST['first_name']) < 2) {
				Message::set('error', 'userfirstname_too_short_or_too_long');
                $error_counter += 1;
			}
			if (!preg_match('/^[a-záöőüűóúéíÁÖŐÜŰÓÚÉÍ]{2,64}$/i', $_POST['first_name'])) {
				Message::set('error', 'userfirstname_does_not_fit_pattern');
                $error_counter += 1;
			}
        */    
				
		// Utónév ellenőrzés
			if( empty($this->request->get_post('last_name')) ) {
				Message::set('error', 'userlastname_field_empty');
                $error_counter += 1;
			}
        /*
			if (strlen($_POST['last_name']) > 64 OR strlen($_POST['last_name']) < 2) {
				Message::set('error', 'userlastname_too_short_or_too_long');
                $error_counter += 1;
			}
			if (!preg_match('/^[a-záöőüűóúéíÁÖŐÜŰÓÚÉÍ]{2,64}$/i', $_POST['last_name'])) {
				Message::set('error', 'userlastname_does_not_fit_pattern');
                $error_counter += 1;		
			}
				
		// Telefonszám ellenőrzés
			if(empty($_POST['phone'])){
				Message::set('error', 'userphone_field_empty');
                $error_counter += 1;
			}
			if (!preg_match('~^(36)[\s-]?([0-9]{1,2})[\s-]?([0-9]{3})[\s-]?([0-9]{4})$~', $_POST['phone'])) {
				Message::set('error', 'userphone_does_not_fit_pattern');
                $error_counter += 1;
			}
        */    
			
		// Jelszó ellenőrzés
			
			// ha üres a password és az ellenőrző password mezö
			if (empty($this->request->get_post('password')) AND empty($this->request->get_post('password_again'))) {
				$password_empty = true;
			}
			else {
				if (empty($this->request->get_post('password')) OR empty($this->request->get_post('password_again'))) {
					Message::set('error', 'password_field_empty');
                    $error_counter += 1;
				}
				if (strlen($this->request->get_post('password')) < 6) {
					Message::set('error', 'password_too_short');
                    $error_counter += 1;
				}
				if ($this->request->get_post('password') !== $this->request->get_post('password_again')) {
					Message::set('error', 'password_repeat_wrong');
                    $error_counter += 1;
				}
			}

		// E-mail ellenőrzés
			if ( empty($this->request->get_post('email')) ) {
				Message::set('error', 'email_field_empty');
                $error_counter += 1;
			}
        /*
			if (strlen($_POST['email']) > 64) {
				Message::set('error', 'email_too_long');
                $error_counter += 1;
			}
			if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
				Message::set('error', 'email_does_not_fit_pattern');
                $error_counter += 1;
			}
        */    

			
		// végrehajtás, ha nincs hiba	
			if ($error_counter == 0) {
				
				// clean the input
				$data['user_name'] = $this->request->get_post('name');
				$data['user_first_name'] = $this->request->get_post('first_name');
				$data['user_last_name'] = $this->request->get_post('last_name');
				$data['user_phone'] = $this->request->get_post('phone');			

				//ha nem létezik a $password_empty változó, vagyis nem üres mindkét password mező	
				if(!isset($password_empty)) {
					// crypt the user's password with the PHP 5.5's password_hash() function, results in a 60 character
					// hash string. the PASSWORD_DEFAULT constant is defined by the PHP 5.5, or if you are using PHP 5.3/5.4,
					// by the password hashing compatibility library. the third parameter looks a little bit shitty, but that's
					// how those PHP 5.5 functions want the parameter: as an array with, currently only used with 'cost' => XX
					$hash_cost_factor = (Config::get('hash_cost_factor') !== null) ? Config::get('hash_cost_factor') : null;
					$data['user_password_hash'] = password_hash($_POST['password'], PASSWORD_DEFAULT, array('cost' => $hash_cost_factor));
				}	
				
				if( !empty($this->request->get_post('email')) ){
					$data['user_email'] = htmlentities($this->request->get_post('email'), ENT_QUOTES, "UTF-8");
				} else {
					$data['user_email'] = null;
				}				
				
                if( $this->request->has_post('user_group') ) {
                    $data['user_role_id'] = $this->request->get_post('user_group');			
                }

				//ha van feltöltve user kép
				if( !empty($this->request->get_post('img_url')) ){
					$path_parts = pathinfo($this->request->get_post('img_url'));
					$data['user_photo'] = htmlentities($path_parts['filename'] . '.' . $path_parts['extension'], ENT_QUOTES, "UTF-8");
				}
				
			// Megvizsgáljuk, hogy van-e már ilyen nevű user (de nem az amit módosítani akarunk)
				$this->query->reset();
				$this->query->set_table(array('users'));
				$this->query->set_columns(array('user_id'));
				$this->query->set_where('user_name', '=', $data['user_name']);
				//itt megadjuk, hogy nem vonatkozik a bejelentkezett user-re (mert ha nem módosítja a nevet akkor már van ilyen user név)
				$this->query->set_where('user_id', '!=', $user_id);
				$result = $this->query->select();
	
				// ha már van ilyen nevű felhasználó
				if (count($result) == 1) {
                    Message::set('error', 'username_already_taken');
					return false;
				}

/*			
				if(!is_null($data['user_email'])){	
				// Megvizsgáljuk, hogy van-e már ilyen email cím user (de nem az amit módosítani akarunk)
					$this->query->reset();
					$this->query->set_table(array('users'));
					$this->query->set_columns(array('user_email'));
					$this->query->set_where('user_email', '=', $data['user_email']);
					//itt megadjuk, hogy nem vonatkozik a bejelentkezett user-re (mert ha nem módosítja a nevet akkor már van ilyen user név)
					$this->query->set_where('user_id', '!=', $user_id);
					$result = $this->query->select();
					
					// ha már van ilyen email cím
					if (count($result) == 1) {
						Message::set('error', 'user_email_already_taken');
						return false;
					}
				}	
*/	
	
				
			// új adatok beírása az adatbázisba (update) a $data tömb tartalmazza a frissítendő adatokat 
				$this->query->reset();
				$this->query->set_table(array('users'));
				$this->query->set_where('user_id', '=', $user_id);
				$result = $this->query->update($data);
                
				if($result >= 0) {
                    // ha a bejelentkezett user adatait módosítjuk, akkor a session adataokat is frissíteni kell
                    if(Session::get('user_id') == $user_id) {
                        // Módosítjuk a $_SESSION tömben is a user adatait!
                        Session::set('user_name', $data['user_name']);
                        Session::set('user_email', $data['user_email']);
                        if(isset($data['user_role_id'])) {
                            Session::set('user_role_id', $data['user_role_id']);
                        }
                        if(isset($data['user_photo'])){
                            Session::set('user_photo', $data['user_photo']);
                        }
                    }
                    Message::set('success', 'user_data_update_success');
					return true;
				}
				else {
					Message::set('error', 'unknown_error');
                    return false;
				}
				
			} else {
				// ha valamilyen hiba volt a form adataiban
				// a hibaüzenetek beíródnak a session-be a metódus elején
				return false;
			}
	}
	
	/**
	 *	Egy user (bizonyos) adatait kérdezi le az adatbázisból
	 *	(user_id, user_name, user_first_name, user_last_name, user_phone, user_email, user_role_id és a role táblából: role_name)
	 *
	 *	@param	$user_id String or Integer
	 *	@return	Array or false
	 */
	public function user_data_query($user_id)
	{
		$this->query->reset();
		$this->query->set_table(array('users'));
		$this->query->set_columns(array('users.user_id', 'users.user_name', 'users.user_first_name', 'users.user_last_name', 'users.user_phone', 'users.user_email', 'users.user_role_id', 'users.user_photo', 'roles.role_name'));
		$this->query->set_join('left', 'roles', 'users.user_role_id', '=', 'roles.role_id');
		$this->query->set_where('user_id', '=', $user_id);
		
		return $this->query->select();
	}


	/**
	 *	Felhasználó képének vágása és feltöltése
	 *	Az $this->registry->params['id'] paraméter értékétől függően feltölti a kiválasztott képet
	 *		upload paraméter esetén: feltölti a kiválasztott képet
	 *		crop paraméter esetén: megvágja a kiválasztott képet és feltölti	
	 *
	 */
	public function user_img_upload()
	{
		if( $this->request->has_params('id') ) {

			include(LIBS . "/upload_class.php");
			
			// Kiválasztott kép feltöltése
			if($this->request->get_params('id') == 'upload') {
				
				// feltöltés helye
				$imagePath = Config::get('user.upload_path');
				
				//képkezelő objektum létrehozása (a kép a szerveren a tmp könyvtárba kerül)	
				$handle = new Upload($_FILES['img']);
				
				if ($handle->uploaded) {
					// kép paramétereinek módosítása
					$handle->file_auto_rename = true;
					$handle->file_safe_name = true;
					//$handle->file_new_name_body   	 = 'lorem ipsum';
					$handle->allowed = array('image/*');
					$handle->image_resize = true;
					$handle->image_x = Config::get('user.width', 600);
					$handle->image_ratio_y = true;
						
					//végrehajtás: kép átmozgatása végleges helyére
					$handle->Process($imagePath);

					if ($handle->processed) {
						//temp file törlése a szerverről
						$handle->clean();
						
						$response = array(
							"status" => 'success',
							//"url" => $handle->file_dst_name,
							"url" => $imagePath . $handle->file_dst_name,
							"width" => $handle->image_dst_x,
							"height" => $handle->image_dst_y
						);
						return json_encode($response); 
				
					} else {
						$response = array(
							"status" => 'error',
							"message" => $handle->error . ': Can`t upload File; no write Access'
						);
						return json_encode($response);  
					}
				
				} else {
					$response = array(
						"status" => 'error',
						"message" => $handle->error . ': Can`t upload File; no write Access'
					);
					return json_encode($response);  				
				}
			}
			
			
			// Kiválasztott kép vágása és vágott kép feltöltése
			if ($this->request->get_params('id') == 'crop') {
		
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
					
				// feltöltés helye
				$imagePath = Config::get('user.upload_path');
				
				//képkezelő objektum létrehozása (a feltöltött kép elérése a paraméter)	
				$handle = new Upload($imgUrl);
				
				// fájlneve utáni random karakterlánc
				$suffix = md5(uniqid());

				if ($handle->uploaded) {
				
				// kép paramétereinek módosítása
					//$handle->file_auto_rename 		 = true;
					//$handle->file_safe_name 		 = true;
					//$handle->file_name_body_add   	 = '_thumb';
					$handle->file_new_name_body   	 = "user_" . $suffix;
					//kép átméretezése
					$handle->image_resize            = true;
					$handle->image_x                 = $imgW;
					$handle->image_ratio_y           = true;
					//utána kép vágása
					$handle->image_crop            	 = array($imgY1, $right_crop, $bottom_crop, $imgX1);
						
					//végrehajtás: kép átmozgatása végleges helyére
					$handle->Process($imagePath);

					if ($handle->processed) {
						// vágatlan forrás kép törlése az upload/user_photo mappából
						$handle->clean();
							
						$response = array(
							"status" => 'success',
							//"url" => $handle->file_dst_name
							"url" => $imagePath . $handle->file_dst_name
						);
						return json_encode($response); 
				
					} else {
						$response = array(
							"status" => 'error',
							"message" => $handle->error . ': Can`t upload File; no write Access'
						);
						return json_encode($response);  
					}
				
				} else {
					$response = array(
						"status" => 'error',
						"message" => $handle->error . ': Can`t upload File; no write Access'
					);
					return json_encode($response);  				
				}
			}
		}
	}


	/**
	 *	(AJAX) A users tábla user_active mezőjének ad értéket
	 *	
	 *	@param	integer	$id	
	 *	@param	integer	$data (0 vagy 1)	
	 * 	@return bool
	 */
	public function change_status_query($id, $data)
	{
		$this->query->reset();
		$this->query->set_table(array('users'));
		$this->query->set_where('user_id', '=', $id);
		$result = $this->query->update(array('user_active' => $data)); 
	
		if($result) {
			return true;
		}
		else {
			return false;
		}		
	}		

	/**
	 * Megviszgálja, hogy az adott id-jű user szuperadmin-e 
	 *
	 * @return true ha superadmin, false ha nem
	 */
	public function is_user_superadmin($id)
	{
		$this->query->reset();
		$this->query->set_table(array('users'));
		$this->query->set_columns(array('user_role_id'));
		$this->query->set_where('user_id', '=', $id);
		$result = $this->query->select(); 
	
		if($result[0]['user_role_id'] == '1') {
			return true;
		} else {
			return false;
		}	
	}
    
	
				/**
				 * Upgrades/downgrades the user's account (for DEFAULT and FACEBOOK users)
				 * Currently it's just the field user_role_id in the database that
				 * can be 1 or 2 (maybe "basic" or "premium"). In this basic method we
				 * simply increase or decrease this value to emulate an account upgrade/downgrade.
				 * Put some more complex stuff in here, maybe a pay-process or whatever you like.
				 */
				/*
				public function changeAccountType()
				{
					if (isset($_POST["user_account_upgrade"]) AND !empty($_POST["user_account_upgrade"])) {

						// do whatever you want to upgrade the account here (pay-process etc)
						// ...
						// ... myPayProcess();
						// ...

						// upgrade account type
						$query = $this->connect->prepare("UPDATE users SET user_role_id = 2 WHERE user_id = :user_id");
						$query->execute(array(':user_id' => $_SESSION["user_id"]));

						if ($query->rowCount() == 1) {
							// set account type in session to 2
							Session::set('user_role_id', 2);
							Message::set('success', 'account_upgrade_successful');
						} else {
							Message::set('error', 'account_upgrade_failed');
						}
					} elseif (isset($_POST["user_account_downgrade"]) AND !empty($_POST["user_account_downgrade"])) {

						// do whatever you want to downgrade the account here (pay-process etc)
						// ...
						// ... myWhateverProcess();
						// ...

						$query = $this->connect->prepare("UPDATE users SET user_role_id = 1 WHERE user_id = :user_id");
						$query->execute(array(':user_id' => $_SESSION["user_id"]));

						if ($query->rowCount() == 1) {
							// set account type in session to 1
							Session::set('user_role_id', 1);
							Message::set('success', 'account_downgrade_successful');
						} else {
							Message::set('error', 'account_downgrade_failed');
						}
					}
				}
				*/		
	

} // end class
?>