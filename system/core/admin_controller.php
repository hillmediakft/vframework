<?php
class Admin_controller extends Controller {

    // felhasználói jogosultság ellenőrzéséhez
    protected $user;

    public function __construct()
    {
        parent::__construct();

        // Authentikáció minden admin oldalra, kivéve a login
        if($this->request->get_controller() != 'login'){
            Auth::handleLogin();
        }

        $this->connect = db::get_connect();
    }
}
?>