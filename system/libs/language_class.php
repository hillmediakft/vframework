<?php 
class Lang {
	
	/**
	 *	Ebbe a tömbbe kerülnek az üzenetek
	 *	Globálisan elérhetőek lesznek (a get() metódussal)
	 */
	private static $msg;

	private function __construct() { }
		
	/**
	 *	Betölti a nyelvi fájlt
     *
     *  @param  string  $lang_code  nyelvi kód pl: en
	 *
	 */
	public static function load($lang_code)
	{
		$lang_file = LANG .'/lang-' . $lang_code . '.php';
		
		if (!file_exists($lang_file)) {
			throw new Exception('HIBA: Nincs ilyen nyelvi file!');
		} 
		
        // fájl betöltése (a file-nak tartalmaznia kell egy $lang tömböt)
        include($lang_file);
        
		if (!isset($lang) OR !is_array($lang)) {
			throw new Exception('HIBA: A betoltott nyelvi file nem megfelelo!');
		}
        
		self::$msg = $lang;
        unset($lang);
	}
    
    public static function get($key)
    {
        return isset(self::$msg[$key]) ? self::$msg[$key] : null;
    }
    
}
?>