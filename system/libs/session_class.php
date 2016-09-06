<?php
/**
* Session class
*
* handles the session stuff. creates session when no one exists, sets and
* gets values, and closes the session properly (=logout). Those methods
* are STATIC, which means you can call them with Session::get(XXX);
*/
class Session
{
	/**
	 *	Nem lehet létrehozni ebből az osztályból objektumpéldányt,
	 *	mert a konstruktor-nak privát a láthatósága!
	 */
	private function __construct() { }

	/**
	 * starts the session
	 */
    public static function init()
    {
        // if no session exist, start the session
        if (session_id() == '') {
            session_start();
        }
    }

	/**
	 * sets a specific value to a specific key of the session
	 * @param mixed $key
	 * @param mixed $value
	 */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }


			/**
		 	 * gets/returns the value of a specific key of the session
			 * @param mixed $key Usually a string, right ?
			 * @return mixed
			 */
			/*
		    public static function get($key)
		    {
		        if (isset($_SESSION[$key])) {
		            return $_SESSION[$key];
		        }
		    }
		    */

    /**
     * Visszaadja a $_SESSION tömb megadott kulcsának értékét
     * A többdimenziós tömb esetén a kulcs megadása: key.subkey 
     * 
     * @param string $key 		pl: 'username' vagy 'userinput.name'
     */
    public static function get($key)
    {
    	if (strpos($key, '.') === false) {
	    	if (isset($_SESSION[$key])) {
	            return $_SESSION[$key];
	        }
    	} else {
			return self::_get_array_value($_SESSION, $key);
    	}
    }

    /**
     * Elem törlése a $_SESSION tömbből
     *
     * @param string $key  
     */
    public static function delete($key)
    {
	    if(isset($_SESSION[$key])){
            unset($_SESSION[$key]);    
        } 
    }

	/**
	 *	Visszadja egy tömb megadott kulcsának értékét
	 *
	 *	Példa a kulcsok megadására: (a többdimenziós tömb elemeit a . karakterrel elválasztva kell megadni, mintha egy útvonal lenne)
	 *		_get_array_value($array, 'userinput.firstname');
	 *
	 *	@param   array   $array    Ebből a tömbből adjuk vissza az adatot
	 *	@param   mixed   $key      A kulcs, amit keresünk
	 *	@return  mixed
	 */
	private static function _get_array_value($array, $key)
	{
		foreach (explode('.', $key) as $key_part)
		{
			if (!array_key_exists($key_part, $array))
			{
				return false;
			}
			$array = $array[$key_part];
		}

		return $array;
	}

	/**
	 * deletes the session (= logs the user out)
	 */
    public static function destroy()
    {
        session_destroy();
    }
	
} //osztály vége
?>