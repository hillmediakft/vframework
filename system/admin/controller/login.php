<?php
class Login extends Admin_controller {
    /**
     * Construct this object by extending the basic Controller class
     */
    function __construct()
    {
        parent::__construct();
        //$this->loadModel('login_model');
    }

	
    /**
     * Default action
	 *
	 * Ez a metódus jeleníti meg a bejelentkezési oldalt!
	 * Ha a bejelentkezési adatok helyesek, bejelentkezteti a felhasználót	
     */
    public function index()
    {
        // ha elküldték a POST adatokat
        if($this->request->has_post()) {
            
            $username = $this->request->get_post('user_name');
            $password = $this->request->get_post('user_password');
            $rememberme = $this->request->has_post('user_rememberme');

            $auth = Auth::instance();
            $login_successful = $auth->login($username, $password, $rememberme);

			// login status vizsgálata
			if ($login_successful) {
                // Sikeres bejelentkezés
                header('location: ' . BASE_URL . 'admin');
                exit;
            }
            // Sikertelen bejelentkezés
            else {
                // hibaüzenetek visszaadása
                foreach ($auth->getError() as $value) {
                    Message::set('error', $value);
                }
				// visszairányítás
				header('location: ' . BASE_URL . 'admin/login');
				exit;
			}			
		}

        // bejelentkezés cookie-val
        $auth = Auth::instance();
        $login_status = $auth->loginWithCookie();
        if ($login_status) {
            header('location: ' . BASE_URL . 'admin');
            exit;
        } else {
            foreach ($auth->getError() as $value) {
               Message::set('error', $value);
            }
            // cookie törlése
            $auth->deleteCookie();
        }

        $this->view = new View();
		$this->view->render('login/tpl_login');
    }
	
    /**
     * The logout action, login/logout
     */
    public function logout()
    {
        //$this->login_model->logout();
        Auth::instance()->logout();
        // redirect
        header('location: ' . BASE_URL);
        exit;
    }

}
?>