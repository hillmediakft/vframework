<?php
/**
 * URI Class
 *
 * @package		vFramework
 * @subpackage	Libraries
 * @category	URI
 * @author		Várnagy brothers
 */
class Uri {

	/**
	 * Az url részeit tároló tömb
	 */
	private static $uri_array = array();

	// megadja, hogy van-e nyelvi kód az url-ben
	private static $lang_code = false;
	
	// megadja, hogy van-e admin modul az url-ben
	private static $admin = false;
	
//-----------------------------------------------------------------	

	private function __construct(){}

	public static function set($key, $value)
	{
		self::$uri_array[$key] = $value;
	}
	
	public static function get($key)
	{
		if(isset(self::$uri_array[$key])){
			return self::$uri_array[$key];
		}	
	}


	public static function get_all()
	{
		return self::$uri_array;
	}

	/**
	 * Feldolgozza az URL-t 
	 *
	 * Statikus tulajdonságokban tárolja az url részeit	     
	 *
	 * @param  	string 	$def_lang 	(a default nyelvi kód)
	 * @param  	array 	$allowed_languages 	(az engedélyezett nyelvi kódok tömbje)
	 * @return	void
	 */
	public static function parse_uri($def_lang, $allowed_languages)
	{
		// Dekódol bármilyen %## kódolást a megadott karakterláncban. A dekódolt stringet adja vissza.
		$uri = urldecode($_SERVER['REQUEST_URI']);
		//kivágjuk az url-ből a BASE_PATH-t ha kell
		$uri = str_replace(BASE_PATH,'',$uri);
		// Eltávolítja a // és ../ jeleket az URI-ból, és levágja a / jeleket az elejéről és a végéről
		$uri = str_replace(array('//', '../'), '/', trim($uri, '/'));
		// Szűri az URI-t
		$uri = filter_var($uri, FILTER_SANITIZE_URL);
		// Ha az URI csak egy / jelet tartalmaz vagy üres, akkor üres karaktert ad vissza 
		$uri = ($uri == '/' || empty($uri)) ? '' : $uri;
		
		// A teljes url
self::set('current_url', BASE_URL . $uri);

// URL SZÉTBONTÁSA
		// Szétbontjuk az uri-t (a sima / jeles elemeket a path tömbelembe teszi, a query_stringet (ha van) a query tömbelembe
		$uri_parts = parse_url($uri);
		// Szétbontjuk a path elemet
		$uri_path_arr = (isset($uri_parts['path'])) ? explode('/', $uri_parts['path']) : array();

// AREA
		$area ='site';
		// Megvizsgáljuk, hogy szerepel-e a tömbben az admin, és hogy a 0-dik vagy 1-dik elem-e.
		if(in_array('admin', $uri_path_arr)){
			$index_admin = array_search('admin', $uri_path_arr);
			if($index_admin <= 1){
				// eltávolítjuk az admin elemet
				unset($uri_path_arr[$index_admin]);
				self::$admin = true;
				$area = 'admin';
			}
		}
self::set('area', $area);

// NYELVI KÓD
		// Megvizsgáljuk, hogy szerepel-e nyelvi kód az url-ban, és ha igen kivágjuk
		foreach($allowed_languages as $lang) {
			if(in_array($lang, $uri_path_arr)){
				$index_lang = array_search($lang, $uri_path_arr);
				if($index_lang <= 1){
					self::$lang_code = true;
					self::set('lang', $lang);
					// eltávolítjuk a nyelvi kód elemet
					unset($uri_path_arr[$index_lang]);
					break;
				}
			}
		}
		// ha nincs nyelvi kód az url-ben - a default lesz a nyelvi kód
		if(self::$lang_code === false){
			self::set('lang', $def_lang);
		}

// QUERY STRING
		if(isset($uri_parts['query'])){
			$query_string = $uri_parts['query'];
			parse_str($query_string, $query_string_arr);
		} else {
			$query_string = '';
			$query_string_arr = array();
		}
self::set('query_string', $query_string);
self::set('query_string_arr', $query_string_arr);
//self::set('query_string_arr', $_GET);

// URI PATH
		$uri_path = implode('/', $uri_path_arr);
self::set('uri_path', $uri_path);

// SITE URL
		$site_url = BASE_URL;
		if(self::$admin === true) {
			$site_url .= $area . '/';
		}
		if(self::$lang_code === true) {
			$site_url .= $lang . '/';
		}
self::set('site_url', $site_url);

	}

	/**
	 * URI string generálás asszociatív tömbből
	 *
	 * @param	array	asszociatív tömb (kulcs/érték párok)
	 * @return	string 	a paraméterek / jellel elválasztva
	 */
	public static function assoc_to_uri($array)
	{
		$temp = array();
		foreach ((array)$array as $key => $val)
		{
			$temp[] = $key;
			$temp[] = $val;
		}
		return implode('/', $temp);
	}
		
}	
?>