<?php
namespace System\Core;
use System\Libs\Config;

class AdminController extends Controller {

    protected $lang;
    protected $langs;

    public function __construct()
    {
        parent::__construct();
        
        $this->lang = LANG;
        $this->langs = Config::get('allowed_languages');

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