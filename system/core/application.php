<?php
namespace System\Core;

use System\Libs\DI;
use System\Libs\Message;
use System\Libs\Auth;

class Application {
	
	protected $request;
	
	public function __construct() 
	{
		// request objektum visszaadása
		$this->request = DI::get('request');
		// area állandó létrehozása
		define('AREA', $this->request->get_uri('area'));


		// Betöltjük az aktuális nyelvnek megfelelő üzenet fájlt
		Message::init('messages_' . AREA, $this->request->get_uri('langcode'));
        
        // Megadjuk az Auth osztály alapbeállításait ('auth.php' config file betöltése)
		Auth::init('auth');


				// nyelvi fájl betöltése
				//$language = new Language();
				//$this->registry->language = $language->load($this->request->get_uri('lang'));


				// hook objektum létrehozása, a rendszer elindítása előtti hook-ok futtatása
				//$this->hooks = new Hooks();
				//$this->hooks->_call_hook($this->request->get_uri('area') . '_pre_system');


		// controller file betöltése és a megfelelő action behívása
		$this->_loadController();
	}
	
	/**
	 * Controller betöltése
	 */
	private function _loadController()
	{
		$controller_name = ucfirst($this->request->get_controller());
		$action_name = $this->request->get_action();
		$parameters = $this->request->get_params();
		$area = ucfirst($this->request->get_uri('area'));

		$controller_class = '\System\\' . $area . '\Controller\\' . $controller_name;
		// ha az osztály létezik
		if (class_exists($controller_class)) {
			// Példányosítjuk a controllert
			$controller = new $controller_class();
			// meghívjuk az action metódust, ha nincs, akkor az index metódust hívjuk meg
			if(method_exists($controller, $action_name)) {
				//$controller->{$action_name}();
				call_user_func_array(array($controller, $action_name), $parameters);
			} else {
				$controller->index();
			}
		}
		else {
			$error_class = '\System\\' . $area . '\Controller\Error';
			$error = new $error_class();
			$error->index();
		}
	}

} // osztály vége
?>