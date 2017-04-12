<?php
namespace System\Libs;

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
     * Megnézi, hogy létezik-e a megadott kulcs a $_SESSION tömbben
     *
	 * @param string $key
     * @return bool
     */
    public static function has($key)
    {
    	//return isset($_SESSION[$key]);

    	if (strpos($key, '.') === false) {
	    	return isset($_SESSION[$key]);
    	} else {
			$result = self::_get_array_value($_SESSION, $key);
			return ($result === false) ? false : true;
    	}
    }


	/**
	 * sets a specific value to a specific key of the session
	 * @param mixed $key
	 * @param mixed $value
	 */
    public static function set($key, $value)
    {
    	if (strpos($key, '.') === false) {
        	$_SESSION[$key] = $value;
    	} else {
    		self::_setArrayValue($_SESSION, $key, $value);
    	}        	
    }

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
	        } else {
	        	return false;
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
	 * deletes the session (= logs the user out)
	 */
    public static function destroy()
    {
        session_destroy();
    }    

	/**
	 *	Visszadja egy tömb megadott kulcsának értékét
	 *
	 *	Példa a kulcsok megadására: (a többdimenziós tömb elemeit a . karakterrel elválasztva kell megadni, mintha egy útvonal lenne)
	 *		_get_array_value($array, 'userinput.firstname');
	 *
	 *	@param   array   $array    Ebből a tömbből adjuk vissza az adatot
	 *	@param   mixed   $key      A kulcs, amit keresünk
	 *	@return  mixed || false
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
	 * Tömb kulcsának és értékének megadása "." karakterrel megadva
	 *
	 *	_setArrayValue($array, 'user_data.user_id', $value);
     *
	 * @param   array   $array  tömb amibe az adatot rakjuk
	 * @param   mixed   $key    kulcsok "." karakterrel elválasztva
	 * @param   mixed   $value  érték
	 * @return  void
	 */
	private static function _setArrayValue(&$array, $key, $value = null)
	{
		$keys = explode('.', $key);

		while (count($keys) > 1)
		{
			$key = array_shift($keys);

			if (!array_key_exists($key, $array))
			{
				$array[$key] = array();
			}

			$array =& $array[$key];
		}

		$array[array_shift($keys)] = $value;
	}
	
} //osztály vége
?>