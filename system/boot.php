<?php
namespace System;

use System\Libs\Config;
use System\Core\Application;
use System\Libs\DI;

//MAPPA beállítások
//define('BASE', dirname(__file__));
define('APP_DIR', 'system'); //Rendszer mappa
define('CORE', APP_DIR . '/Core'); //core mappa
define('LIBS', APP_DIR . '/Libs'); //libs mappa
define('CONFIG', APP_DIR . '/config'); //config mappa
define('MESSAGE', APP_DIR . '/message'); //message mappa
define('ADMIN', APP_DIR . '/Admin'); //admin mappa
define('SITE', APP_DIR . '/Site'); //site mappa
//define('UPLOADS', 'uploads' . DS); //uploads mappa
define('UPLOADS', 'uploads/'); //uploads mappa

define('SITE_ASSETS', 'public/site_assets/');
define('SITE_CSS', 'public/site_assets/css/');
define('SITE_JS', 'public/site_assets/js/');
define('SITE_IMAGE', 'public/site_assets/images/');

define('ADMIN_ASSETS', 'public/admin_assets/');
define('ADMIN_CSS', 'public/admin_assets/css/');
define('ADMIN_JS', 'public/admin_assets/js/');
define('ADMIN_IMAGE', 'public/admin_assets/img/');

define('MULTILANG_SITE', false);

/**
 * LOCAL SERVER és ONLINE SERVER beállítások
 */
if (isset($_SERVER['SERVER_ADDR']) && ($_SERVER['SERVER_ADDR'] == '127.0.0.1' || $_SERVER['SERVER_ADDR'] == '::1')) {
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
    // url fordítások betöltése
    //Config::load('url_translation');
} else {
    //ONLINE
    // error_reporting(0);
    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    define('BASE_URL', 'http://xxxx.hu/'); //Az oldal elérési útjának beállítása
    define('BASE_PATH', ''); //A domainnév utáni elérési út beállítása
    define('ENV', 'production'); //online éles környezet
    //db adatok	
    Config::load('db_online');
    // alap config tömb betöltése a configba
    Config::load('common_config');
    // érzékeny email adatok betöltése
    Config::load('email', 'email');
    // url fordítások betöltése
    //Config::load('url_translation');
}

//---!! DIC !!---------------------

DI::setContainer(new \Pimple\Container());

DI::set('connect', function() {
    $settings = Config::get('db');
    $db = new \System\Libs\DB($settings['name'], $settings['host'], $settings['user'], $settings['pass']);
    return $db->create();
});

DI::set('uri', function() {
    return new \System\Libs\Uri(Config::get('language_default'), Config::get('allowed_languages'));
});

DI::set('router', function() {
    return new \System\Libs\Router();
});

DI::set('request', function($c) {
    return new \System\Libs\Request($c['uri'], $c['router']);
});

DI::set('response', function() {
    return new \System\Libs\Response();
});

DI::set('auth', function() {
    return new \System\Libs\Auth();
});

DI::set('language', function($c) {
    return new \System\Libs\language($c['request']->get_uri('langcode'));
});

// helpers ---------------
DI::set('file_helper', function() {
    return new \System\Helper\File();
});
DI::set('str_helper', function() {
    return new \System\Helper\Str();
});
DI::set('url_helper', function() {
    return new \System\Helper\Url();
});
DI::set('arr_helper', function() {
    return new \System\Helper\Arr();
});
DI::set('num_helper', function() {
    return new \System\Helper\Num();
});
DI::set('html_helper', function() {
    return new \System\Helper\Html();
});

/*
  DI::factory('query', function($c){
  return new \System\Libs\Query($c['connect']);
  });
 */

// application objektum példányosítása	
$application = new Application();
?>