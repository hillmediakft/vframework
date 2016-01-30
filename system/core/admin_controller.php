<?php 
class Admin_controller extends Controller {
	
	public function __construct()
	{
		parent::__construct();
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
            Message::set('error', 'Nincs jogosultsága a művelet végrehajtásához!');
            $target_url = str_replace(BASE_URL . "admin/","",$target_url);
            Util::redirect($target_url);
        }
        return true;
    }        
        
}
?>