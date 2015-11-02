<?php 
class Application {
	
	private $registry;
	
	public function __construct($registry) 
	{
		$this->registry = $registry;	
		$this->request = $this->registry->request;

		// Az area-nak megfelelő leszármazott Model osztály betöltése (site, vagy admin)	
		//$this->load_model();

        // Beállítjuk, hogy az üzenetek melyik modulra vonatkozzanak (message_site vagy message_admin)
        Message::set_area($this->request->get_uri('area'));
		// Betöltjük az aktuális nyelvnek megfelelő üzenet fájlt
		Message::load('messages_' . $this->request->get_uri('area'), $this->request->get_uri('langcode'));
        

				// nyelvi fájl betöltése
				//$language = new Language();
				//$this->registry->language = $language->load($this->registry->lang);


				// hook objektum létrehozása, a rendszer elindítása előtti hook-ok futtatása
				//$this->hooks = new Hooks();
				//$this->hooks->_call_hook($this->registry->area . '_pre_system');


		// controller file betöltése és a megfelelő action behívása
		$this->load_controller();
	}
	
	/**
	 * Controller betöltése
	 */
	private function load_controller()
	{
		// site vagy admin controller osztály behívása
		//include_once(CORE . '/' . $this->request->get_uri('area') . '_controller.php');

		$controller_name = $this->request->get_controller();
		$action_name = $this->request->get_action();

		// Először is betölti a megfelelő controller fájlt (ha betölthető), az url első paramétere alapján.
		$file = 'system/' . $this->request->get_uri('area') . '/controller/' . $controller_name . '.php';
	
		if(!file_exists($file)) {	
			require_once ('system/' . $this->request->get_uri('area') . '/controller/error.php');
			$error = new Error();
			$error->index();
		} else {
			require_once($file);
			// Példányosítjuk a controllert
			$controller = new $controller_name();
		
			// meghívjuk az action metódust, ha nincs, akkor az index metódust hívjuk meg
			if(method_exists($controller, $action_name)) {
				$controller->{$action_name}();
			} else {
				$controller->index();
			}
		}
	}

	/**
	 * Az area-nak megfelelő Model osztály betöltése (site, vagy admin) 
	 */
	private function load_model()
	{
		include_once(CORE . '/' . $this->request->get_uri('area') . '_model.php');
	}
	
} // osztály vége
?>