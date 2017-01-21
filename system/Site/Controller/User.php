<?php 
namespace System\Site\Controller;

use System\Core\SiteController;
use System\Core\View;
use System\Libs\Config;
use System\Libs\Message;
use System\Libs\Auth;
use System\Libs\DI;
use System\Libs\Validate;
use System\Libs\Session;
use System\Libs\Emailer;

class User extends SiteController {

	private $email_verify = true;

	function __construct()
	{
		parent::__construct();
		$this->loadModel('user_model');
	}

	/**
	 *	Ne legyen 'sima' user oldal
	 */
	public function index()
	{
		$this->response->redirect('error');
	}

	/**
	 *	Felhasználó bejelentkezés
	 */
	public function login()
	{
		if($this->request->is_ajax()){

	        // ha elküldték a POST adatokat
	        if($this->request->has_post()) {
	            
	            $username = $this->request->get_post('user_name');
	            $password = $this->request->get_post('user_password');
	            $rememberme = $this->request->has_post('user_rememberme');

	            // $auth = Auth::instance();
	            $auth = DI::get('auth');
	            $login_successful = $auth->login($username, $password, $rememberme);

				// login status vizsgálata
				if ($login_successful) {
	                // Sikeres bejelentkezés esetén ez megy vissza a javascriptnek
					$this->response->json(array(
						'status' => 'logged_in'
					));
	                //$this->response->redirect();
	            }
	            // Sikertelen bejelentkezés
	            else {
	                // hibaüzenetek visszaadása
	                foreach ($auth->getError() as $value) {
	                    //Message::set('error', $value);
	                    $error_messages[] = Message::show($value);
	                }
	                // üzenet a javascriptnek
	                $this->response->json(array(
						'status' => 'error',
						'message' => $error_messages
					));
	                //$this->response->redirect();
				}			
			}

/*
	            // bejelentkezés cookie-val
	            $auth = DI::get('auth');
	            $login_status = $auth->loginWithCookie();
	            if ($login_status) {
	                $this->response->redirect();
	            } else {
	                foreach ($auth->getError() as $value) {
	                   Message::set('error', $value);
	                }
	                // cookie törlése
	                $auth->deleteCookie();
	            }
*/


		} else {
			$this->response->redirect('error');
		}
	}
    
    /**
     * Kijelentkezés
     */
    public function logout()
    {
        DI::get('auth')->logout();
        // átirányítás a front-oldalra
        $this->response->redirect();
    }  
	
	/**
	 *	Új felhasználó regisztrációja
	 *	Ha valamilyen hiba történik a regisztráció során, akkor a visszaadott json message elemének tömbnek kell lennie (mert lehet több hiba is egyszerre)
	 *
	 */
	public function register()
	{
		if($this->request->has_post()) {

	        // adatok a $_POST tömbből
	        $post_data = $this->request->get_post();

	        // validátor objektum létrehozása
	        $validate = new Validate();

	        // szabályok megadása az egyes mezőkhöz (mező neve, label, szabály)
	        $validate->add_rule('user_name', 'username', array(
	            'required' => true,
	            'min' => 3
	        ));
	        $validate->add_rule('user_email', 'email', array(
	            'required' => true,
	            'email' => true
	            // 'max' => 64
	        ));        
	        $validate->add_rule('password', 'password', array(
	            'required' => true,
	            'min' => 6
	        ));
	        $validate->add_rule('password_again', 'password', array(
	            'required' => true,
	            'matches' => 'password'
	        ));

	        // üzenetek megadása az egyes szabályokhoz (szabály_neve, üzenet)
	        $validate->set_message('required', ':label_field_empty');
	        $validate->set_message('min', ':label_too_short');
	        $validate->set_message('matches', ':label_repeat_wrong');
	        $validate->set_message('email', ':label_does_not_fit_pattern');
	        //$validate->set_message('max', ':label_too_long');

	        // mezők validálása
	        $validate->check($post_data);

	        // a hibaüzenetek ebbe a tömbbe kerülnek
	        $error_messages = array();

	        // HIBAELLENŐRZÉS - ha valamilyen hiba van a form adataiban
	        if(!$validate->passed()){
	            foreach ($validate->get_error() as $error_msg) {
	            	// egy tömbbe kell rakni a javascriptnek küldendő hibaüzeneteket
	                $error_messages[] = Message::show($error_msg);
	            }

	            $this->response->json(array(
	            	'status' => 'error',
	            	'message' => $error_messages
	            ));
	        }
	        else {
	        // végrehajtás, ha nincs hiba 
	            $user = array();
	            $user['name'] = $this->request->get_post('user_name');
	            $user['email'] = $this->request->get_post('user_email');

	            $user['role_id'] = 3;
	            $user['provider_type'] = NULL;

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
	                	$error_messages[] = Message::show('username_already_taken');
	                }
					// ellenőrzés, hogy létezik-e már ilyen email cím az adatbázisban
	                if ($this->user_model->checkEmail($user['email'])) {
	                	$error_messages[] = Message::show('user_email_already_taken');
	                }
	                // ha a felhasználói név, vagy az email cím már létezik
	                if (!empty($error_messages)) {
	                    $this->response->json(array(
	                    	'status' => 'error',
	                    	'message' => $error_messages
	                    ));	
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
                    $this->response->json(array(
                    	'status' => 'error',
                    	'message' => array(Message::show('account_creation_failed'))
                    ));	
	            }

	            // Ezután jön az ellenörző email küldés (ha az $email_verify tulajdonság értéke true)
	            // ha sikeres az ellenőrzés, visszatér true-val, ellenkező esetben a visszatér false-al
	            if ($this->email_verify === true) {
	                // ellenőrző email küldése, ha az ellenőrző email küldése sikertelen: felhasználó törlése az databázisból
	                if ($this->_sendVerificationEmail($last_inserted_id, $user['name'], $user['email'], $user['activation_hash'])) {
		                $this->response->json(array(
		                	'status' => 'success',
		                	'message' => array(Message::show('verification_mail_sending_successful') )
		                ));	

	                } else {
	                    $this->user_model->delete($last_inserted_id);
	                    $this->response->json(array(
	                    	'status' => 'error',
	                    	'message' => array(Message::show('verification_mail_sending_failed'))
	                    ));
	                }
	            }

	            // ha nincs email ellenőrzés, és minden ellenőrzés sikeres
                $this->response->json(array(
                	'status' => 'success',
                	'message' => array(Message::show('user_successfully_created'))
                ));	
	        }
		}
	}

    /**
     * sends an email to the provided email address
     *
     * @param int       $user_id                    user's id
     * @param string    $user_name                  felhasznalo neve
     * @param string    $user_email                 user's email
     * @param string    $user_activation_hash       user's mail verification hash string

     * @return boolean
     */
    private function _sendVerificationEmail($user_id, $user_name, $user_email, $user_activation_hash)
    {
    	$from_email = 'info_ka@example.com'; // adatbázisból! 
    	$from_name = 'teszt name 44'; // adatbázisból!
    	$to_email = $user_email;
    	$to_name = $user_name;
    	$subject = 'Subject helye...'; // adatbázisból!
    	$template_data = array(
			'base_url' => BASE_URL,
			'title' => 'Verify registration teszt',
			'user_name' => $user_name,
			'user_email' => $user_email,
			'user_id' => $user_id,
			'user_activation_hash' => $user_activation_hash
    	);
    	$template_name = 'user_register_verification';

    	$emailer = new Emailer($from_email, $from_name, $to_email, $to_name, $subject, $template_data, $template_name);
    	
    	// küldés smtp-vel
    	//$emailer->setSmtp();

    	return $emailer->send();
    }

    /**
     * Fogadja a regisztráció hitelesítő email linkjéről érkező kérést
     *
     * @param integer $id
     * @param string $activation_hash
     */
    public function verify($id, $activation_hash)
    {
		// új regisztráció ellenőrzése
		$result = $this->user_model->verifyNewUser((int)$id, $activation_hash);

		if($result) {
			Message::set('success', 'account_activation_successful');
		} else {
			Message::set('error', 'account_activation_failed');
		}

		$this->response->redirect();
    }

	/**
	 *	Új jelszó küldése a felhasználónak (elfelejtett jelszó esetén)
     *  - lekérdezi, hogy van-e a $_POST-ban kapott email címmel rendelkező felhasználó
     *  - generál egy 8 karakter hosszú jelszót és egy new_password_hash-t
     *  - az új password hash-t az adatbázisba írja
     *  - elküldi email-ben az új jelszót a felhasználónak
     *  - ha az email küldése sikertelen, visszaírja az adatbázisba a régi password hash-t
	 */
	public function forgottpw()
	{
		if($this->request->is_ajax()){
            
            // a felhasználó email címe, amire küldjük az új jelszót
            $user_email = $this->request->get_post('user_email');
            
            // lekérdezzük, hogy ehhez az email címhez tartozik-e user (lekérdezzük a nevet, és a password hash-t)
            $result = $this->user_model->getPasswordHash($user_email);
            // ha nincsen ilyen e-mail címmel regisztrált felhasználó 
            if(empty($result)){
                $this->response->json(array(
                  'status' => 'error',
                  'message' => 'Nincsen ilyen e-mail címmel regisztrált felhasználó!'
                ));
            }
            
            // a felhasználó neve
            $user_name = $result[0]['name'];
            // a felhasználó régi titkosított jelszava
            $old_password_hash = $result[0]['password_hash'];
                  
            // 8 karakter hosszú új jelszó generálása (str_shuffle összekeveri a stringet, substr levágja az első 8 karaktert)
            $new_password = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8);
            $hash_cost_factor = (Config::get('hash_cost_factor') !== null) ? Config::get('hash_cost_factor') : null;
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT, array('cost' => $hash_cost_factor));            
        
            // új jelszó hash beírása az adatbázisba
            $result = $this->user_model->setNewPassword($user_email, $new_password_hash);
            // ha hiba történt az adatbázisba íráskor
            if($result === false){
                $this->response->json(array(
                    'status' => 'error',
                    'message' => 'Adatbázis hiba!'
                ));
            }
            
            // új jelszót tartalmazó email küldése
            if ($this->_sendForgottenPwEmail($user_email, $user_name, $new_password)) {
                $this->response->json(array(
                	'status' => 'success',
                	'message' => Message::show('Az új jelszót elküldtük az email címére.')
                ));	

            } else {
                // régi password hash visszaírása az adatbázisba
                $this->user_model->setNewPassword($user_email, $old_password_hash);
                $this->response->json(array(
                  'status' => 'error',
                  'message' => Message::show('Új jelszó küldése sikertelen.')
                ));
            }

		} else {
			$this->response->redirect('error');
		}
		
	}   

    /**
     * Új jelszót tartalmazó emailt küld a felhasználónak
     *
     * @param string    $user_email
     * @param string    $user_name
     * @param string    $new_password
     * @return boolean
     */
    private function _sendForgottenPwEmail($user_email, $user_name, $new_password)
    {
    	$from_email = 'info_ka@example.com'; // adatbázisból! 
    	$from_name = 'ingatlanok-hitelek'; // adatbázisból!
    	$to_email = $user_email;
    	$to_name = $user_name;
    	$subject = 'Subject helye...'; // adatbázisból!
    	$template_data = array(
			'base_url' => BASE_URL,
			'title' => 'Elfelejtett jelszó teszt',
			'user_name' => $user_name,
			'new_password' => $new_password
    	);
    	$template_name = 'forgotten_password';

    	$emailer = new Emailer($from_email, $from_name, $to_email, $to_name, $subject, $template_data, $template_name);
    	
    	// küldés smtp-vel
    	//$emailer->setSmtp();

    	return $emailer->send();
    }

}
?>