<?php
class Admin_controller extends Controller {

    public function __construct()
    {
        parent::__construct();

        // Authentikáció minden admin oldalra, kivéve a login
        if($this->request->get_controller() != 'login'){
            // Auth::handleLogin();
           	if (!Auth::check()) {
            	Util::redirect('login');	
           	} 
        }
        
    }
}
?>