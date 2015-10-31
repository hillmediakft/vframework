<?php 
//MAPPA beállítások

//define('BASE', dirname(__file__));
define('CORE', 'system/core'); //core mappa
define('LIBS', 'system/libs'); //libs mappa
define('CONFIG', 'system/config'); //config mappa
define('MESSAGE', 'system/message'); //lang mappa
define('ADMIN', 'system/admin'); //admin mappa
define('SITE', 'system/site'); //site mappa
define('UPLOADS', 'uploads/'); //uploads mappa

define('SITE_ASSETS', 'public/site_assets/'); //
define('SITE_CSS', 'public/site_assets/css/'); //
define('SITE_JS', 'public/site_assets/js/'); //
define('SITE_IMAGE', 'public/site_assets/image/'); //

define('ADMIN_ASSETS', 'public/admin_assets/'); //
define('ADMIN_CSS', 'public/admin_assets/css/'); //
define('ADMIN_JS', 'public/admin_assets/js/'); //
define('ADMIN_IMAGE', 'public/admin_assets/img/'); //

// config class betöltése
include_once(LIBS.'/config_class.php');

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
    
	//db adatok
    Config::load('db_local');
    // alap config tömb betöltése a configba
    Config::load('common_config');
	// érzékeny email adatok betöltése
	Config::load('email', 'email');
	
}
else {
    //ONLINE
	error_reporting(E_ALL);
	ini_set("display_errors", 1);
	
	define('BASE_URL', 'http://vframework.onlinemarketingguru.hu/'); //Az oldal elérési útjának beállítása
	define('BASE_PATH', ''); //A domainnév utáni elérési út beállítása

	//db adatok	
    Config::load('db_online');
    // alap config tömb betöltése a configba
    Config::load('common_config');
	// érzékeny email adatok betöltése
	Config::load('email', 'email');  
    

}
?>