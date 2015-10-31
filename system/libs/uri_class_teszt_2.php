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

	private $valid_uri = true;

	private $port;
	private $host;
	private $scheme;

	private $request_uri;
	
	private $path; // Az URI a query string nélkül
	private $path_arr; // Az URI path részei tömbben
	
	private $query; // query string
	private $query_arr = array();
	
	// Az uri részeit tartalmazó tömb (path, query, scheme, host...)
	private $uri_parts = array();

	//private $current_url; // A teljes url
	//private $site_url; // base url + lang + modul

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
		$this->scheme = $_SERVER['REQUEST_SCHEME'];

		$this->get_request_uri();
		$this->_parse_uri();
		$this->_check_admin();
		$this->_check_lang_code();
		
		//$this->_validate_url();




echo "<hr>Request uri";
var_dump($this->request_uri);

echo "<hr>Uri_parts";
var_dump($this->uri_parts);

echo "<hr>Path:";
$this->get_path();
var_dump($this->path);

echo "<hr>Path_arr";
var_dump($this->path_arr);

echo "<hr>Query string";
var_dump( $this->get_query_string() );

echo "<hr>Query string array";
var_dump($this->get_query_string_arr());

echo "<hr>Area:";
var_dump($this->area);

echo "<hr>Van e lang code:";
var_dump($this->has_langcode());

echo "<hr>Lang:";
var_dump($this->get_langcode());

echo "<hr>Host:";
var_dump($this->get_host());

echo "<hr>Site url:";
var_dump($this->get_site_url());

echo "<hr>Current url:";
var_dump($this->get_current_url());



die('x');


	}


public function get_request_uri()
{
	$this->request_uri = urldecode($_SERVER['REQUEST_URI']);
	$this->request_uri = str_replace(BASE_PATH,'', trim($this->request_uri, '/'));
	//$this->request_uri = str_replace(array('//', '../'), '/', trim($this->request_uri, '/'));
	return $this->request_uri;
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
						break;
					}
				}

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
		if($index_admin <= 1){
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
 * Visszaadja a host nevet (pl.: http://www.valami.hu)
 *
 * @return string	
 */
public function get_host()
{
	return $this->scheme . '://' . $this->host;
}

/**
 * Visszaadja, hogy tartalmaz-e az url nyelvi kódot
 *
 * @return boolean 
 */
public function has_langcode()
{
	return $this->is_lang;
}

/**
 * Visszaadja a nyelvi kódot
 *
 * @return string
 */
public function get_langcode()
{
	return $this->lang;
}

/**
 * Visszaadja az area értékét
 *
 * @return string
 */
public function get_area(){
	return $this->area;
}


/**
 * Visszaadja az uri path-t (request uri a query string nélkül)
 *
 * @return string
 */
public function get_path()
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
	return $this->path;
}

/**
 * Visszadja a query stringet
 *
 * @return string
 */
public function get_query_string()
{
	return $this->uri_parts['query'];
}

/**
 * Visszadja a query string-ből létrehozott tömböt (mint a $_GET tömb)
 *
 * @return array
 */
public function get_query_string_arr()
{
	if(!empty($this->uri_parts['query'])){
		parse_str($this->uri_parts['query'], $query_string_arr);
		return $query_string_arr;
	} else {
		return array();
	}
}

public function get_site_url()
{
	$site_url = BASE_URL;
	if($this->is_admin === true) {
		$site_url .= $this->area . '/';
	}
	if($this->is_lang === true) {
		$site_url .= $this->lang . '/';
	}
	return $site_url;
}

public function get_current_url()
{
	return $this->get_host() . '/' . $this->request_uri; 
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