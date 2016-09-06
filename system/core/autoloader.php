<?php
/**
 * Autoloader osztály
 *
 * Először a core mappából utána a libs mappából próbálja betölteni az osztályt
 *
 * @author Vucu
 */
class Autoloader {

	public function __construct()
	{
    	spl_autoload_register(array($this, 'autoload'));
    	//spl_autoload_register(array($this, 'autoload_phpMailer'));
	}

    public function autoload($class_name)
    {
		$file = CORE . '/' . strtolower($class_name) . '.php';
		if (file_exists($file)){
			require $file;
			return;
		}
		$file = LIBS . '/' . strtolower($class_name) . '_class.php';
		if (file_exists($file)){
			require $file;
			return; 
		}
		$file = LIBS . '/' . strtolower($class_name) . '.php';
		if (file_exists($file)){
			require $file;
			return; 
		}
    }
/*
	public function autoload_phpMailer($class_name)
	{
		$file = LIBS . '/PHPMailer/class.' . strtolower($class_name) . '.php';
		if (file_exists($file)){
			require $file;
			return; 
		}			
	}
*/
}
?>