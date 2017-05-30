<?php
define('DS', DIRECTORY_SEPARATOR); // oprendszere válogatja
define('APP_ROOT', __DIR__ . DS); // definiáltuk a gyökérkönyvtárat

// composer autoload betöltése
require __DIR__ . '/vendor/autoload.php';

// alkalmazás indítás
include_once('system/boot.php');
?>