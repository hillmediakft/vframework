<?php	
	
	/**
	 * Routes
	 * 
	 * További lehetséges URL minták:
	 * 
	 * hirek/2014/10/22/ez-egy-hir-cime (opcionális / jel)
	 * 'hirek/:year/:month/:day/:title/?' => array('news/get_news_by_id', 'year', 'month', 'day', 'title')
	 * 
	 * felhasznalok/kereses/param1/val1/param2/val2 (opcionális / jel)
	 * 'felhasznalok/kereses/:any/:any/:any/:any/?' => array('users/search')
	 *
	 * felhasznalok/barmi (opcionális /)
	 * 'felhasznalok/:any/?' => array('users/index', 'status'),
	 * 
	 */
	
	$routes = array(
	//users útvonalak
	'(users)/(login)/?' => array('error/index'),
	'(users)/(logout)/?' => array('error/index'),
	//'(felhasznalok)/(bejelentkezes)/?' => array('users/login'),
	'(felhasznalok)/(kijelentkezes)/?' => array('users/logout'),
	//'(felhasznalok)/(regisztracio)/?' => array('users/register'),
	//'(felhasznalok)/(feliratkozas)/?' => array('users/subscribe_newsletter'),



	
	
	// /controller/action/param1/val1/param2/val2 (opcionális / jel)
	':controller/:action/:any/:any/:any/:any/?' => array('$1/$2'),
	
	// /controller/action/param1/val1 (opcionális / jel)
	':controller/:action/:any/:any/?' => array('$1/$2'),
	
	':controller/:action/:id/:any/:any/?' => array('$1/$2', 'id'),	
	
	// /controller/action/id (opcionális / jel)
	':controller/:action/:id/?' => array('$1/$2', 'id'),
	
	// /controller/action/valami (opcionális / jel)
	':controller/:action/:any/?' => array('$1/$2', 'id'),
	
	// /controller/action (opcionális /)
	':controller/:action/?' => array('$1/$2'),

	
	// /controller (opcionális / jel, "index" action)
	':controller/?' => array('$1'),	

	// base url	
	'' => array('home/index'),

	// nem létező oldal	
	'_error' => array('error/index')
	
	);

?>