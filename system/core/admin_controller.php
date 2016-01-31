<?php

class Admin_controller extends Controller {

    // felhasználói jogosultság ellenőrzéséhez
    protected $user;

    public function __construct() {
        parent::__construct();
        //require_once "system/libs/logged_in_user.php";
        $this->connect = db::get_connect();
        $this->user = new Logged_in_user();
    }

    /**
     * 	Hozzáférési jogosultság ellenőrzése
     *
     * 	@param	string	$perm       a jogosultság kódja pl. menu_home - hozzáférés a home menühöz
     * 	@param	string	$target_url jogosultság hiányában ide irányítjuk a felhasználót
     * 	@return	bool	ha van jogosultsága a felhasználónak, akkor true értéket ad vissza
     */
    public function check_access($perm, $target_url = 'home') {
        if (!$this->user->hasAccess($perm)) {

            $sql = "SELECT perm_message FROM permissions WHERE perm_key = :perm";
            $sth = $this->connect->prepare($sql);
            $sth->execute(array(":perm" => $perm));
            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                $result = $row["perm_message"];
            }
            Message::set('error', $result);
            $target_url = str_replace(BASE_URL . "admin/", "", $target_url);
            Util::redirect($target_url);
        }
        return true;
    }

}

?>