<?php
namespace System\Libs;

/**
 * Kivétel kezelő osztály
 * 
 * A kivételt formázott dobozban jeleníti meg
 *
 * @package	V-Framework
 * @author	Várnagy
 */
class ErrorExceptionHandler {

    private $errorConstants = array(
      1 => 'Error',
      2 => 'Warning',
      4 => 'Parse error',
      8 => 'Notice',
      16 => 'Core Error',
      32 => 'Core Warning',
      256 => 'User Error',
      512 => 'User Warning',
      1024 => 'User Notice',
      2048 => 'Strict',
      4096 => 'Recoverable Error',
      8192 => 'Deprecated',
      16384 => 'User Deprecated',
      32767 => 'All'
    );

    /**
     * Beállítja a kivételkezelést és a hibakezelést végző metódusokat
     *
     */
    public function __construct() {
        set_error_handler(array($this, 'errorHandler'));
        set_exception_handler(array($this, 'exceptionHandler'));
        register_shutdown_function(array($this, 'fatalErrorHandler'));

        ini_set('display_errors', false);
    }

    /**
     * A kivételek formázott megjelenítése
     *
     */
    public function exceptionHandler($exception) {

        // header küldése, hogy az ékezet jól jelenjenek meg
        header('Content-type: text/html; charset=utf-8');
        echo '<span style = "text-align: left; font-family: arial; background-color: #f2dede; border: 1px solid #ebccd1; color: #a94442; display: block; margin: 1em 0; padding: .33em 6px; border-radius: 4px;">';
        echo '<span style = "font-size: 30px"> Hiba történt!</span><br>';
        echo '<b>Hibaüzenet:</b>' . $exception->getMessage() . ' - [code: ' . $exception->getCode() . ']<br />';
        echo '<b>Fájl:</b>' . $exception->getFile() . '<br />';
        echo '<b>Sor:</b>' . $exception->getLine();
        echo '</span>';
    }

    /**
     * Hibák megjelenítése
     * 
     * A következő hibákat nem kezeli: E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, 
     * E_COMPILE_ERROR, E_COMPILE_WARNING
     */
    public function errorHandler($errno, $errstr, $errfile, $errline) {
        $errString = (array_key_exists($errno, $this->errorConstants)) ? $this->errorConstants[$errno] : $errno;
        header('Content-type: text/html; charset=utf-8');
        echo '<span style = "text-align: left; font-family: arial; background-color: #f2dede; border: 1px solid #ebccd1; color: #a94442; display: block; margin: 1em 0; padding: .33em 6px; border-radius: 4px;">';
        echo '<span style = "font-size: 30px"> Hiba történt!</span><br>';
        echo 'Hiba: ' . $errString . ': ' . $errstr . '<br>';
        echo 'Fájl: ' . $errfile . '<br>';
        echo 'Sor: ' . $errline;
        echo '</span>';
    }

    /**
     * Hibák megjelenítése
     * 
     * A következő hibákat nem kezeli: E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, 
     * E_COMPILE_ERROR, E_COMPILE_WARNING
     */
    public function fatalErrorHandler() {
        $error = error_get_last();
        $error_type = (array_key_exists($error['type'], $this->errorConstants)) ? $this->errorConstants[$error['type']] : $error['type'];
//check if it's a core/fatal error, otherwise it's a normal shutdown
        if ($error !== NULL && $error['type'] === E_ERROR) {
            header('Content-type: text/html; charset=utf-8');
            echo '<span style = "text-align: left; font-family: arial; background-color: #f2dede; border: 1px solid #ebccd1; color: #a94442; display: block; margin: 1em 0; padding: .33em 6px; border-radius: 4px;">';
            echo '<span style = "font-size: 30px"> Hiba történt!</span><br>';
            echo 'Hiba: ' . $error['message'] . ': ' . $error_type . '<br>';
            echo 'Fájl: ' . $error['file'] . '<br>';
            echo 'Sor: ' . $error['line'];
            echo '</span>';
            
        }
    }

}

?>