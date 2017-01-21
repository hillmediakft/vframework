<?php
namespace System\Core;

//use System\Libs\Auth;

class AdminController extends Controller {

    public function __construct()
    {
        parent::__construct();
        
        // Authentikáció minden admin oldalra, kivéve a login
        /*
        if($this->request->get_controller() != 'login'){
           	if (!Auth::check()) {
                $this->response->redirect('admin/login');	
           	} 
        }
        */
     
    }
}
?>