<?php 
class Application {
	
	public $registry;
	
	public function __construct($registry) 
	{
		$this->registry = $registry;	

		$request = $this->registry->request;


var_dump($request->get_uri('base'));
var_dump($request->get_uri('request_uri'));
var_dump($request->get_uri('current_url'));
var_dump($request->get_uri('site_url'));
var_dump($request->get_uri('area'));
var_dump($request->get_uri('langcode'));
var_dump($request->get_uri('is_langcode'));
var_dump($request->get_uri('path'));
var_dump($request->get_uri('query'));
var_dump($request->get_uri('query_arr'));

die('stop');


		// Az area-nak megfelelő leszármazott Model osztály betöltése (site, vagy admin)	
		$this->load_model();

        // Beállítjuk, hogy az üzenetek melyik modulra vonatkozzanak (message_site vagy message_admin)
        Message::set_area($this->registry->uri->get_area());
		// Betöltjük az aktuális nyelvnek megfelelő üzenet fájlt
		Message::load('messages_'.$this->registry->uri->get_area(), $this->registry->uri->get_langcode());
               
		// router betöltése (controller, action és paraméterek megadása)
		$this->load_router();
		
//var_dump($this->registry);
//die('end');
		// controller file betöltése és a megfelelő action behívása
		$this->load_controller();
	}
	
	/**
	 *	Router elindítása
	 */
	private function load_router()
	{
		$router = new Router();
		$router->find($this->registry->uri->get_path(), $this->registry->uri->get_area());

			$this->registry->controller = $router->controller;
			$this->registry->action = $router->action;
			$this->registry->params = $router->params;
	}
	
	/**
	 * Controller betöltése
	 */
	private function load_controller()
	{
	// Először is betölti a megfelelő controller fájlt (ha betölthető), az url első paramétere alapján.
		$file = 'system/' . $this->registry->uri->get_area() . '/controller/' . $this->registry->controller . '.php';
	
		if(!file_exists($file)) {	
			require_once ('system/' . $this->registry->uri->get_area() . '/controller/error.php');
			$error = new Error();
			$error->index();
		} else {
			require_once($file);
			// Példányosítjuk a controllert
			$controller = new $this->registry->controller();
		
			// meghívjuk az action metódust, ha nincs, akkor az index metódust hívjuk meg
			if(method_exists($controller, $this->registry->action)) {
				$controller->{$this->registry->action}();
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
		include_once(CORE.'/model_' . $this->registry->uri->get_area() . '.php');
	}
	
} // osztály vége
?>