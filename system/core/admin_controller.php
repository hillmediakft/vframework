<?php
namespace System\Core;
use System\Libs\Auth;
// use System\Libs\Util;

class Admin_controller extends Controller {

    public function __construct()
    {
        parent::__construct();
        
        // Authentikáció minden admin oldalra, kivéve a login
        if($this->request->get_controller() != 'login'){
            // Auth::handleLogin();
           	if (!Auth::check()) {
            	// Util::redirect('login');
                $this->response->redirect('admin/login');	
           	} 
        }
     
    }
}
?>