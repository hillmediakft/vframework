<?php
class Login extends Admin_controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    function __construct()
    {
        parent::__construct();
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
		if($this->request->has_post('submit_login')) {
			// perform the login method, put result (true or false) into $login_successful
			$login_successful = $this->login_model->login();

			// check login status
			if ($login_successful) {
				// if YES, then move user to /admin (btw this is a browser-redirection, not a rendered view!)
				header('location: ' . BASE_URL . 'admin');
				exit;
			} else {
				// if NO, then move user to /admin/login (login form) again
				header('location: ' . BASE_URL . 'admin/login');
				exit;
			}			
		}

        $this->view = new View();
		$this->view->render('login/tpl_login');
    }

	
    /**
     * The logout action, login/logout
     */
    public function logout()
    {
        $this->login_model->logout();
        // redirect user to base URL
		header('location: ' . BASE_URL);
		exit;
    }

	
			/**
			 * Login with cookie
			 */
			function loginWithCookie()
			{
				// run the loginWithCookie() method in the login-model, put the result in $login_successful (true or false)
				$login_successful = $this->login_model->loginWithCookie();

				if ($login_successful) {
					$location = $this->login_model->getCookieUrl();
					if ($location) {
						header('location: ' . BASE_URL . $location);
					} else {
						header('location: ' . BASE_URL . 'admin');
					}
				} else {
					// delete the invalid cookie to prevent infinite login loops
					$this->login_model->deleteCookie();
					// if NO, then move user to login/index (login form) (this is a browser-redirection, not a rendered view)
					header('location: ' . BASE_URL . 'admin/login');
				}
			}


                                /**
                                 * Register page
                                 * Show the register form (with the register-with-facebook button). We need the facebook-register-URL for that.
                                 */
                                function register()
                                {
                            		if($this->request->has_post('register')){
                            		
                            			$registration_successful = $this->login_model->registerNewUser();

                            			if ($registration_successful == true) {
                            				header('location: ' . BASE_URL . 'admin/login');
                            				exit;
                            			} else {
                            				header('location: ' . BASE_URL . 'admin/login/register');
                            			}	exit;
                            		}	

                                    $this->view = new View();
                            		$this->view->render('login/tpl_register');
                                }

	
                                    /**
                                     * Verify user after activation mail link opened
                                     * @param int $user_id user's id
                                     * @param string $user_activation_verification_code sser's verification token
                                     */
                                    function verify()
                                    {
                                        $this->login_model->verifyNewUser($this->request->get_params('user_id'), $this->request->get_params('user_activation_verification_code'));
                                        $this->view = new View();
                                        $this->view->render('login/tpl_verify', true);
                                    }

                                    /**
                                     * Request password reset page
                                     */
                                    function requestPasswordReset()
                                    {
                                		//ha a form el lett küldve
                                		if($this->request->has_post('request_password_reset')) {
                                			$this->login_model->requestPasswordReset();
                                			header('location:' . BASE_URL . 'admin/login');
                                			exit;
                                		}
                                		
                                		$this->view = new View();
                                        $this->view->render('login/tpl_requestpasswordreset', true);
                                    }


                                    /**
                                     * Verify the verification token of that user (to show the user the password editing view or not)
                                     * @param string $user_name username
                                     * @param string $verification_code password reset verification token
                                     */
                                    function verifyPasswordReset()
                                    {
                                        if ($this->login_model->verifyPasswordReset($this->request->get_params('user_name'), $this->request->get_params('verification_code'))) {
                                            // get variables for the view
                                            $this->view->user_name = $this->request->get_params('user_name');
                                            $this->view->user_password_reset_hash = $this->request->get_params('verification_code');
                                			$this->view->render('login/tpl_changepassword', true);
                                        } else {
                                            header('location: ' . BASE_URL . 'admin/login');
                                        }
                                    }

                                    /**
                                     * Set the new password
                                     */
                                    function setNewPassword()
                                    {
                                        // try the password reset (user identified via hidden form inputs ($user_name, $verification_code)), see
                                        // verifyPasswordReset() for more
                                        $this->login_model->setNewPassword();
                                        // regardless of result: go to index page (user will get success/error result via feedback message)
                                        header('location: ' . BASE_URL . 'admin/login');
                                    }
}
?>