<?php
class Admin_controller extends Controller {

    public function __construct()
    {
        parent::__construct();

        // Authentikáció minden admin oldalra, kivéve a login
        if($this->request->get_controller() != 'login'){
            Auth::handleLogin();
        }

        // model betöltése
        $this->loadModel($this->request->get_controller() . '_model');
    }
}
?>