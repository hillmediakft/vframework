<?php
/**
 * vFramework
 *
 * MVC alapú keretrendszer
 *
 * @package		vFramework
 * @author		Várnagy brothers
 * @since		Version 1.0
 */

// ------------------------------------------------------------------------

/**
 * URI Class
 *
 * uri feldolgozó metódusok
 *
 * @package		vFramework
 * @subpackage	Libraries
 * @category	URI
 * @author		Várnagy brothers
 */
class Uri {

	public $uri = '';
		
	/**
	 *	A teljes jelenlegi url
	 */
	public $current_url = '';

	/**
	 *	base url + lang + modul
	 */ 
	public $site_url = '';
	
	/**
	 *	Az URI a query string nélkül pl.:
	 * example.com/user/search/name/joe?order=1&view=grid
	 * az eredmény: example.com/user/search/name/joe
	 */
	public $uri_path = '';
	public $query_string = '';
	public $query_string_arr = array();
	public $area = '';
	
	//nyelvi kód
	public $lang = '';
	//megadja, hogy van-e nyelvi kód az url-ben
	public $lang_code = false;
	// nyelvi kódok tömbje
	public $languages = array('hu','en','de','ru');
	
//-----------------------------------------------------------------	

	function __construct()
	{
        //default nyelvi kód
        $this->lang = Config::get('language_default');
		
        $this->get_uri();
		$this->check_admin_and_lang();
		$this->set_site_url();
		$this->get_uri_without_query_string();
		$this->query_string_to_assoc();
	}

	
	/**
	 * Az URI Stringet adja vissza, és szűrést végez rajta (eltávolítja a "rossz" karaktereket)    
	 * Kivágja az URI-ból a BASE_PATH-t
	 *
	 * @return	beállítja az $uri tulajdonság értékét
	 */
	public function get_uri()
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
		
		// Tisztított uri a tulajdonságba 
		$this->uri = $uri;
		
		// Tisztított uri a current_url tulajdonságba (az elejéhez hozzáfűzzük a base url-t)
		$this->current_url = BASE_URL . $uri;
	}

				
	/**
	 * Area meghatározása: admin vagy site
	 * Megvizsgálja, hogy az admin string szerepel-e az uri-ban, illetve hogy az "admin" string 
	 * az első (0) vagy a második (1) a tömbben (akkor a második, ha nyelvi string előzi meg).
	 * Így az uri * későbbi részében szerepelhet az admin string
	 *
	 * @return	beállítja az $area és a $query_string (ha van) tulajdonság értékét 
	 */
	public function check_admin_and_lang()
	{
	// Szétbontjuk az uri-t (a sima / jeles elemeket a path tömbelembe teszi, a query_stringet (ha van) a query tömbelembe
		$str = parse_url($this->uri);

		// Szétbontjuk a path elemet
		if (isset($str['path'])) {
			$arr = explode('/', $str['path']);
		} else{
			$arr = array();
		}
	// Beállítjuk a $query_string tulajdonság értékét (ha van query_string)
		if (isset($str['query'])) {
			$this->query_string = $str['query'];
		} 
		
	// Megvizsgáljuk, hogy szerepel-e a tömbben az admin, és hogy a 0-dik vagy 1-dik elem-e.
		if(in_array('admin', $arr) && (array_search('admin', $arr) == 0 || array_search('admin', $arr) == 1)){
			// Kivágjuk az uri-ból az admin-t (vagy csak az elsőt: preg_replace par3 :1, vagy az összeset, akkor -1)
			$this->uri = preg_replace('@admin\/?@', '', $this->uri, 1);
			// Beállítjuk az $area tulajdonság értékét
			$this->area = 'admin';
		}	
		else {
			// Beállítjuk az $area tulajdonság értékét
			$this->area ='site';
		}
		
	// Megvizsgáljuk, hogy szerepel-e nyelvi kód az uri-ban, és ha igen kivágjuk
		foreach($this->languages as $value) {
			if(in_array($value,$arr)){
				// ha szerepel az $arr tömbben a nyelvi kód, akkor beállítjuk a $lang_code tulajdonság értékét true-ra (ezzel azt adjuk meg, hogy van nyelvi kód az url-ben)
				$this->lang_code = true;
				// ha szerepel az $arr tömbben a nyelvi kód, akkor beállítjuk a $lang tulajdonság értékét
				$this->lang = $value;
				// Eltávolítjuk az uri elejéről a nyelvi kódot
				$this->uri = preg_replace("@^$value\/?@", '', $this->uri, 1);
			}
		}
	}


	/**
	 * A query stringben szereplő paramétereket asszociatív tömbben adja vissza
	 * example.com/user/search?name=joe&location=UK&gender=male
	 * array (
	 *			name => joe
	 *			location => UK
	 *			gender => male
	 *		 )
	 *
	 * @return	array	beállítja a $query_string_arr tulajdonság értékét
	 */
	public function query_string_to_assoc()
	{
		if(!empty($this->query_string) && (strpos($this->query_string,'=') !== false)) {
			foreach (explode('&', $this->query_string) as $couple) {
				list ($key, $val) = explode('=', $couple);
				$params[$key] = $val;
			}	
		$this->query_string_arr = $params;		
		}
		
	}
	
	
	/**
	 * Az URI a query string nélkül pl.:
	 * example.com/user/search/name/joe?order=1&view=grid
	 * az eredmény: example.com/user/search/name/joe
	 * 
	 * @return  string	Beállítja az $uri_path tulajdonság értékét
	 */
	public function get_uri_without_query_string()
	{
		if (strpos($this->uri,'?') !== false) {
			$uri_temp_arr = array();
			$uri_temp_arr = explode('?', $this->uri);
			$this->uri_path = $uri_temp_arr[0];
			unset($uri_temp);
		}
		else {
			$this->uri_path = $this->uri;
		}
	}
	
	/**
	 *	site_url beállítása
	 */
	public function set_site_url()
	{
		$temp_url = BASE_URL;
		if($this->area != 'site') {
			$temp_url .= $this->area . "/";
		}
		if($this->lang_code === true) {
			$temp_url .= $this->lang . "/";
		}
		$this->site_url = $temp_url;				
	}
	
	
	
// --------------------------------------------------------------------
				
				
				/**
				 * Filter segments for malicious characters - ezt esetleg még használhatjuk
				 *
				 * @access	private
				 * @param	string
				 * @return	string
				 */
				public function _filter_uri($str)
				{
					if ($str != '' && $this->config->item('permitted_uri_chars') != '' && $this->config->item('enable_query_strings') == FALSE)
					{
						// preg_quote() in PHP 5.3 escapes -, so the str_replace() and addition of - to preg_quote() is to maintain backwards
						// compatibility as many are unaware of how characters in the permitted_uri_chars will be parsed as a regex pattern
						if ( ! preg_match("|^[".str_replace(array('\\-', '\-'), '-', preg_quote($this->config->item('permitted_uri_chars'), '-'))."]+$|i", $str))
						{
							show_error('The URI you submitted has disallowed characters.', 400);
						}
					}

					// Convert programatic characters to entities
					$bad	= array('$',		'(',		')',		'%28',		'%29');
					$good	= array('&#36;',	'&#40;',	'&#41;',	'&#40;',	'&#41;');

					return str_replace($bad, $good, $str);
				}



				/**
				 * URI string generálás asszociatív tömbből
				 *
				 * @param	array	asszociatív tömb (kulcs/érték párok)
				 * @return	string 	a paraméterek / jellel elválasztva
				 */
				public function assoc_to_uri($array)
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