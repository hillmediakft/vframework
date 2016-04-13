<?php
//alap configurációs beállítások betöltése
include_once('system/core/base_config.php');

	// autolader osztály betöltése
	include_once(CORE . '/autoloader.php');
	$autoloader = new Autoloader();
	// spl_autoload_register(array($autoloader, 'autoload'));

	// sima autoloader
	// include_once(CORE . '/autoload_sima.php');


// config file betöltése
//require_once('system/core/config.php');
//require_once('system/core/config_email.php');
//require_once('system/core/config_login.php');

/*
include_once(CORE.'/application.php');
include_once(CORE.'/controller.php');
include_once(CORE.'/model.php');
include_once(CORE.'/view.php');

include_once(LIBS.'/registry_class.php');
include_once(LIBS.'/uri_class.php');
include_once(LIBS.'/request_class.php');
include_once(CORE.'/router.php');

include_once(LIBS.'/db_class.php');
include_once(LIBS.'/query_class.php');
include_once(LIBS.'/util_class.php');
include_once(LIBS.'/session_class.php');
include_once(LIBS.'/auth_class.php');
include_once(LIBS.'/message_class.php');
include_once(LIBS.'/replacer_class.php');
*/

// checking for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit('Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !');
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once(LIBS.'/password_compatibility_library.php');
}


	// Registry objektum létrehozása
	$registry = Registry::get_instance();
	// uri objektum példányosítása a registry-be
	$uri = new Uri(Config::get('language_default'), Config::get('allowed_languages'));
	// router objektum példányosítása		
	$router = new Router();
	// request objektum példányosítása
	$registry->request = new Request($uri, $router);


// application objektum példányosítása	
$application = new Application($registry);
?>