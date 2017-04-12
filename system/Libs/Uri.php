<?php
namespace System\Libs;

/**
 * URI Class
 *
 * @package		vFramework
 * @subpackage	Libraries
 * @category	URI
 * @author		Várnagy brothers
 */
class Uri {

	private $uri_contents = array();

	//private $valid_uri = true;

	private $port;
	private $host;
	private $scheme;

	private $request_uri; // uri path + query string
	
	private $path; // A request uri a query string, admin és nyelvi kód nélkül!
	private $path_full; // A request uri a query string nélkül
	private $path_arr; // Az uri path részei tömbben
	
	private $query; // query string
	private $query_arr = array();
	
	// Az uri részeit tartalmazó tömb (path, query, scheme, host...)
	private $uri_parts = array();

	// area
	private $area = 'site';
	// megadja, hogy van-e admin modul az url-ben
	private $is_admin = false;
	
	// nyelvi kód
	private $lang;
	// megadja, hogy van-e nyelvi kód az url-ben
	private $is_lang = false;
	// engedélyezett nyelvi kódok
	private $allowed_languages;

	// az admin modul indexe a path_arr tömbben
	private $index_admin;
	// a nyelvi kód indexe a path_arr tömbben
	private $index_lang;

	
//-----------------------------------------------------------------	

	public function __construct($default_lang, $allowed_languages)
	{
		$this->lang = $default_lang;
		$this->allowed_languages = $allowed_languages;

		//$this->port = $_SERVER['SERVER_PORT'];
		$this->host = $_SERVER['SERVER_NAME'];
		$this->scheme = (isset($_SERVER['REQUEST_SCHEME'])) ? $_SERVER['REQUEST_SCHEME'] : 'http';

		$this->_request_uri();
		$this->_parse_uri();
		$this->_check_admin();
		$this->_check_lang_code();

		$this->_base();
		$this->_is_langcode();
		$this->_langcode();
		$this->_area();
		$this->_pathFull();
		$this->_path();
		//$this->_path_widt_langcode();
		$this->_query_string();
		$this->_query_arr();
		$this->_site_url();
		$this->_current_url();
		
		//$this->_validate_url();
		
	}


	private function set($key, $value)
	{
		$this->uri_contents[$key] = $value;
	}
	
	public function get($key = null)
	{
		if(is_null($key)){
			return $this->uri_contents;
		} else {
			
			if(isset($this->uri_contents[$key])){
				return $this->uri_contents[$key];
			} else {
				return null;
			}
		
		}
	}

    /**
     * 	Visszaadja a jelenlegi url-t a paraméterben megadott nyelvi kóddal módosítva
     *
     * 	@param	String	$lang_code	(nyelvi kód)
     * 	@return	String
     */
    public function get_url_with_language($lang_code)
    {
        $lang = ($lang_code == 'hu') ? '' : $lang_code . '/';
        $area = ($this->area == 'site') ? '' : $this->area . '/';
        $query_string = (empty($this->query)) ? '' : '?' . $this->query;
        return BASE_URL . $area . $lang . $this->path . $query_string;
    }

	/**
	 * Beállítja a request uri-t
	 * Kivágja a BASE_PATH-t, és trimmeli a / jeleket a string végeiről
	 */
	private function _request_uri()
	{
		$this->request_uri = urldecode($_SERVER['REQUEST_URI']);
		$this->request_uri = str_replace(BASE_PATH,'', trim($this->request_uri, '/'));
		//$this->request_uri = str_replace(array('//', '../'), '/', trim($this->request_uri, '/'));
		$this->set('request_uri', $this->request_uri);
	}

	/**
	 * URI részekre bontása
	 */
	private function _parse_uri()
	{
		$this->uri_parts = parse_url($this->request_uri);

		$this->uri_parts['scheme'] = (isset($this->uri_parts['scheme'])) ? $this->uri_parts['scheme'] : $this->scheme;
		$this->uri_parts['host'] = (isset($this->uri_parts['host'])) ? $this->uri_parts['host'] : $this->host;
		$this->uri_parts['path'] = (isset($this->uri_parts['path'])) ? $this->uri_parts['path'] : '';
		$this->uri_parts['query'] = (isset($this->uri_parts['query'])) ? $this->uri_parts['query'] : '';
		$this->uri_parts['fragment'] = (isset($this->uri_parts['fragment'])) ? $this->uri_parts['fragment'] : '';

		// Szétbontjuk a path elemet
		$this->path_arr = ($this->uri_parts['path'] == '') ? array() : explode('/', $this->uri_parts['path']);
		//$this->path_arr = (isset($this->uri_parts['path'])) ? explode('/', $this->uri_parts['path']) : array();
	}

	/**
	 * Megvizsgáljuk, hogy szerepel-e a path_arr tömbben az admin, és hogy a 0-dik vagy 1-dik elem-e.
	 */
	private function _check_admin()
	{
		if(in_array('admin', $this->path_arr)){
			$index_admin = array_search('admin', $this->path_arr);
			if($index_admin == 0){
					$this->index_admin = $index_admin;

					// eltávolítjuk az admin elemet
					//unset($this->path_arr[$index_admin]);

				$this->is_admin = true;
				$this->area = 'admin';
			}
		}
	}

	/**
	 * Megvizsgáljuk, hogy szerepel-e (engedélyezett) nyelvi kód az url-ben (0. vagy 1. helyen)
	 * Beállítja a is_lang, lang, index_lang tulajdonságok értékeit
	 */
	private function _check_lang_code()
	{
		foreach($this->allowed_languages as $lang) {
			if(in_array($lang, $this->path_arr)){
				$index_lang = array_search($lang, $this->path_arr);
				if($index_lang <= 1){
					$this->is_lang = true;
					$this->lang = $lang;
					$this->index_lang = $index_lang;

						// eltávolítjuk a nyelvi kód elemet
						//unset($this->path_arr[$index_lang]);
					
					break;
				}
			}
		}
	}

	/**
	 * Beállítja a bázis url-t (pl.: http://www.valami.hu)
	 */
	private function _base()
	{
		$base = $this->scheme . '://' . $this->host;
		$this->set('base', $base);
	}

	/**
	 * Beállítja, megadja, hogy tartalmaz-e az url nyelvi kódot
	 */
	private function _is_langcode()
	{
		$this->set('is_langcode', $this->is_lang);
	}

	/**
	 * Beállítja a nyelvi kódot
	 */
	private function _langcode()
	{
		$this->set('langcode', $this->lang);
	}

	/**
	 * Beállítja az area értékét
	 */
	private function _area()
	{
		$this->set('area', $this->area);
	}

	/**
	 * Teljes path beállítása, amiben benne van a modul neve (admin) és a nyelvi kód
	 */
	private function _pathFull()
	{
		$this->set('path_full', $this->uri_parts['path']);
	}

	/**
	 * Beállítja az uri path-t (request uri a query string, a modul neve (admin) és a nyelvi kód nélkül!)
	 */
	private function _path()
	{
		$temp = $this->path_arr;
		if(isset($this->index_admin)){
			unset($temp[$this->index_admin]);
		}
		if(isset($this->index_lang)){
			unset($temp[$this->index_lang]);
		}	
		$this->path = implode('/', $temp);
		unset($temp);
		
		$this->set('path', $this->path);
	}

	/**
	 * Beállítja az uri path_with_langcode-t (request uri a query string, a modul neve (admin) nélkül!)
	 * Ha 
	 */
	/*
	private function _path_widt_langcode()
	{
		$langcode = '';
		if ($this->get('is_langcode') && $this->get('langcode') != 'hu') {
			$langcode = $this->get('langcode') . '/';
		}
		$path_with_langcode = $langcode . $this->get('path');
		$this->set('path_with_langcode', $path_with_langcode);
	}
	*/

	/**
	 * Beállítja a query stringet
	 *
	 * @return string
	 */
	private function _query_string()
	{
		$this->query = $this->uri_parts['query'];
		$this->set('query', $this->uri_parts['query']);
	}

	/**
	 * Beállítja a query string-ből létrehozott tömböt (mint a $_GET tömb)
	 */
	private function _query_arr()
	{
		if(!empty($this->uri_parts['query'])){
			parse_str($this->uri_parts['query'], $query_arr);
		} else {
			$query_arr = array();
		}

		$this->set('query_arr', $query_arr);
	}

	/**
	 * Beállítja a site url-t
	 */
	private function _site_url()
	{
		$site_url = BASE_URL;
		if($this->is_admin === true) {
			$site_url .= $this->area . '/';
		}
		if($this->is_lang === true) {
			$site_url .= $this->lang . '/';
		}
		$this->set('site_url', $site_url);
	}

	/**
	 * Beállítja a current url-t
	 */
	private function _current_url()
	{
		$this->set('current_url', BASE_URL . $this->request_uri);
	}


		/**
		 * URI string generálás asszociatív tömbből
		 *
		 * @param	array	asszociatív tömb (kulcs/érték párok)
		 * @return	string 	a paraméterek / jellel elválasztva
		 */
		private function assoc_to_uri($array)
		{
			$temp = array();
			foreach ((array)$array as $key => $val)
			{
				$temp[] = $key;
				$temp[] = $val;
			}
			return implode('/', $temp);
		}



			private function _validate_url()
			{
				if(!filter_var($this->get_current_url(), FILTER_SANITIZE_URL)){
					$this->valid_uri = false;
					exit('rossz_url');
				}

				foreach ($this->path_arr as $value) {
					if ($value == '') {
						$this->valid_uri = false;
						exit('rossz_url_2');
						//break;
					}
				}

			}

/*
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
							
*/
}
?>