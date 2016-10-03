<?php
define('DS', DIRECTORY_SEPARATOR); // oprendszere válogatja
define('APP_ROOT', __DIR__ . DS); // definiáltuk a gyökérkönyvtárat

	// autolader osztály betöltése
	//include_once(CORE . '/autoloader.php');
	//$autoloader = new Autoloader();
	//spl_autoload_register(array($autoloader, 'autoload'));


// composer autoload betöltése
require __DIR__ . '/vendor/autoload.php';

// autoloader betöltése
//include_once('system/autoloader_new.php');



//alap configurációs beállítások betöltése
//include_once('system/config/base_config.php');


// alkalmazás indítás
include_once('system/boot.php');


/*
	// checking for minimum PHP version
	if (version_compare(PHP_VERSION, '5.3.7', '<')) {
	    exit('Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !');
	}
*/


/*
	// Registry objektum létrehozása
	$registry = Registry::getInstance();
	// uri objektum példányosítása a registry-be
	$uri = new Uri(Config::get('language_default'), Config::get('allowed_languages'));
	// router objektum példányosítása		
	$router = new Router();
	// request objektum példányosítása
	$registry->request = new Request($uri, $router);


// application objektum példányosítása	
$application = new Application($registry);
*/

?>