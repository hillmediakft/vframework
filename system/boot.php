<?php 
namespace System;

use System\Libs\Config;
use System\Core\Application;
use System\libs\DI;


//MAPPA beállítások

//define('BASE', dirname(__file__));
define('APP_DIR', 'system'); //Rendszer mappa
define('CORE', APP_DIR . '/core'); //core mappa
define('LIBS', APP_DIR . '/libs'); //libs mappa
define('CONFIG', APP_DIR . '/config'); //config mappa
define('MESSAGE', APP_DIR . '/message'); //message mappa
define('ADMIN', APP_DIR . '/admin'); //admin mappa
define('SITE', APP_DIR . '/site'); //site mappa
//define('UPLOADS', 'uploads' . DS); //uploads mappa
define('UPLOADS', 'uploads/'); //uploads mappa

define('SITE_ASSETS', 'public/site_assets/' );
define('SITE_CSS', 'public/site_assets/css/');
define('SITE_JS', 'public/site_assets/js/');
define('SITE_IMAGE', 'public/site_assets/image/');

define('ADMIN_ASSETS', 'public/admin_assets/');
define('ADMIN_CSS', 'public/admin_assets/css/');
define('ADMIN_JS', 'public/admin_assets/js/');
define('ADMIN_IMAGE', 'public/admin_assets/img/');


/**
* LOCAL SERVER és ONLINE SERVER beállítások
*/
if (isset($_SERVER['SERVER_ADDR']) && ($_SERVER['SERVER_ADDR'] == '127.0.0.1' || $_SERVER['SERVER_ADDR'] == '::1'))
{
	//LOCAL
	error_reporting(E_ALL);
	ini_set("display_errors", 1);

    define('BASE_URL', 'http://vframework/'); //Az oldal elérési útjának beállítása
	define('BASE_PATH', ''); //A domainnév utáni elérési út beállítása
	define('ENV', 'development'); //fejlesztői környezet
    
	//db adatok
    Config::load('db_local');
    // alap config tömb betöltése a configba
    Config::load('common_config');
	// érzékeny email adatok betöltése
	Config::load('email', 'email');
	
}
else {
    //ONLINE
	// error_reporting(0);
	error_reporting(E_ALL);
	ini_set("display_errors", 1);
	
	define('BASE_URL', 'http://xxx/'); //Az oldal elérési útjának beállítása
	define('BASE_PATH', ''); //A domainnév utáni elérési út beállítása
	define('ENV', 'production'); //online éles környezet

	//db adatok	
    Config::load('db_online');
    // alap config tömb betöltése a configba
    Config::load('common_config');
	// érzékeny email adatok betöltése
	Config::load('email', 'email');  
    
}

//---!! DIC !!---------------------

DI::setContainer(new \Pimple\Container());

/*
	DI::set('connect_old', function() {
		return \System\Libs\DB::get_connect();
	});
*/

	DI::set('connect', function() {
		$settings = Config::get('db');
		$db = new \System\Libs\DB($settings['host'], $settings['name'], $settings['user'], $settings['pass']);
		return $db->create();
	});
/*
		DI::set('connect2', function() {
			$settings = Config::get('db');
			try {
				$connect = new \PDO('mysql:host=' . $settings['host'] . ';dbname=' . $settings['name'] . ';charset=utf8', $settings['user'], $settings['pass']);
				if(ENV == 'development'){
					$connect->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
				}
			}
			catch(\PDOException $e) {
				die('Database error: ' . $e->getMessage());
			}

			return $connect;
		});
*/		



	DI::set('uri', function() {
		return new \System\Libs\Uri(Config::get('language_default'), Config::get('allowed_languages'));
	});

	DI::set('router', function() {
		return new \System\Libs\Router();
	});

	DI::set('request', function($c){
		return new \System\Libs\Request($c['uri'], $c['router']);
	});

	DI::set('response', function(){
		return new \System\Libs\Response();
	});

	DI::set('auth', function(){
		return new \System\Libs\Auth();
	});

// helpers ---------------
	DI::set('file_helper', function(){
		return new \System\Helper\File();
	});
	DI::set('str_helper', function(){
		return new \System\Helper\Str();
	});
	DI::set('url_helper', function(){
		return new \System\Helper\Url();
	});

/*
	DI::factory('query', function($c){
		return new \System\Libs\Query($c['connect']);
	});
*/


// alkalmazás indítása OLD

	// Registry objektum létrehozása
	// $registry = Registry::getInstance();
	// uri objektum példányosítása a registry-be
	// $uri = new Uri(Config::get('language_default'), Config::get('allowed_languages'));
	// router objektum példányosítása		
	// $router = new Router();
	// request objektum példányosítása
	// $request = new Request($uri, $router);
	

	// application objektum példányosítása	
	$application = new Application();

?>