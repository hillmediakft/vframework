<?php 
/**
* Request osztály
*/
class Request {

	private $uri; // uri objektum
	private $router; // router objektum

	/**
	 * Az engedélyezett szűrőket tartalamzó tömb
	 */
	private $allowed_filters = array('int', 'integer', 'bool', 'boolean', 'remove_danger_tags');


	public function __construct($uri, $router)
	{
		$this->uri = $uri;
		$this->router = $router;
		$this->router->find($this->uri->get('path'), $this->uri->get('area'));
	}

	/**
	 * Visszadja a controllert
	 * @return string
	 */
	public function get_controller()
	{
		return $this->router->controller;
	}

	/**
	 * Visszaadja az actiont
	 * @return string
	 */
	public function get_action()
	{
		return $this->router->action;
	}

	/**
	 * Visszaadja a paraméterek tömbjét, ha a nem adunk meg paramétert.
	 * Vagy a paraméterban megadott nevű paramétert adja vissza.
	 *
	 * @param string $index 	A paraméter neve
	 * @return mixed
	 */
	public function get_params($index = null)
	{
		if(is_null($index)){
			return $this->router->params;
		}
		if(array_key_exists($index, $this->router->params)){
			return $this->router->params[$index];
		} else {
			throw new Exception('Nincs ' . $index . ' nevu elem a parameterek kozott!');
			exit();
		}
	}

	/**
	 * Ellenőrzi, hogy létezik-e a paraméter a router objektum params tömbjében
	 *
	 * @param string $index 	A paraméter neve
	 * @return boolean
	 */
	public function has_params($index)
	{
		//return empty($this->router->params[$index]);
		return isset($this->router->params[$index]);
	}

	/**
	 * Visszaadja az uri objektumban tárolt url részeket
	 * Ha nem adunk meg paramétert, akkor visszadja az url részeit tartalmazó tömböt	
	 *
	 * @param string $name 	(az url valamelyik része pl.: current_url, base, path, site_url ...)
	 * @return string|array
	 */
	public function get_uri($name = null)
	{
		return $this->uri->get($name);
	}

	/**
	 * Adatok visszaadása a $_POST tömbből
	 *
	 * @param string $key 	 
	 * @param string $filter	 
	 * @param string $default_value
	 * @return mixed	 
	 */
	public function get_post($key = NULL, $filter = NULL, $default_value = NULL)
	{
		if(!is_null($filter) && !in_array($filter, $this->allowed_filters)){
			throw new Exception("Nem megengedett filter a request class get_post metodusaban");
			exit();
		}

		// adatok a $_POST tömbből
		$value = $this->_fetch_from_array($_POST, $key);

		return $this->_filter($filter, $value, $default_value);
	}

	/**
	 * Adatok visszaadása a $_GET tömbből
	 *
	 * @param string $key 	 
	 * @param string $filter	 
	 * @param string $default_value
	 * @return mixed	 
	 */
	public function get_query($key, $filter = NULL, $default_value = NULL)
	{
		if(!is_null($filter) && !in_array($filter, $this->allowed_filters)){
			throw new Exception("Nem megengedett filter a request class get_query metodusaban");
			exit();
		}

		// adatok a $_GET tömbből
		$value = $this->_fetch_from_array($_GET, $key);

		return $this->_filter($filter, $value, $default_value);
	}


	/**
	 * HTTP Referer visszaadása a $_SERVER szuperglobális tömbből
	 */
	public function get_httpreferer()
	{
		return (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null);
	}

	/**
	 * Visszadja a kérés metódusát (get vagy post)
	 */
	public function get_method()
	{
		return strtolower($_SERVER['REQUEST_METHOD']);
	}


	/**
	 * Ellenőrzi, hogy létezik-e a paraméterként kapott index a $_REQUEST szuperglobális tömbben
	 *
	 * @return boolean
	 */
	public function has($index)
	{
		return isset($_REQUEST[$index]);
	}

	/**
	 * Ellenőrzi, hogy létezik-e a paraméterként kapott index a $_POST szuperglobális tömbben
	 *
	 * @return boolean
	 */
	public function has_post($index)
	{
		return isset($_POST[$index]);
	}

	/**
	 * Ellenőrzi, hogy létezik-e a paraméterként kapott index a $_GET szuperglobális tömbben
	 *
	 * @return boolean
	 */
	public function has_query($index)
	{
		return isset($_GET[$index]);
	}

	/**
	 * Ellenőrzi, hogy a kérés metódusa GET volt-e
	 * 
	 * @return boolean
	 */
	public function is_get()
	{
		return strtolower($_SERVER['REQUEST_METHOD']) == 'get';
	}
		
	/**
	 * Ellenőrzi, hogy a kérés metódusa POST volt-e
	 * 
	 * @return boolean
	 */
	public function is_post()
	{
		return strtolower($_SERVER['REQUEST_METHOD']) == 'post';
	}

	/**
	 * Ellenőrzi, hogy a Ajax hívás történt-e
	 *
	 * @return boolean
	 */
	public function is_ajax()
	{
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}




	/**
	 * Adatok visszaadása a szuperglobális tömbökből
	 *
	 * @param	array	$array		$_GET, $_POST stb.
	 * @param	mixed	$index		Kulcs, aminek az értékét vissza kell adni az $array tömbből
	 * @return	mixed
	 */
	private function _fetch_from_array($array, $index = NULL)
	{
		// Ha az $index értéke NULL, az $index az $array tömb kulcsait fogja tartalmazni
		if(is_null($index)){
			$index = array_keys($array);
		}

		// allow fetching multiple keys at once
		if (is_array($index)) {
			$output = array();
			foreach ($index as $key) {
				$output[$key] = $this->_fetch_from_array($array, $key);
			}

			return $output;
		}

		if (isset($array[$index])) {
			$value = $array[$index];
		}
		elseif (($count = preg_match_all('/(?:^[^\[]+)|\[[^]]*\]/', $index, $matches)) > 1) {
			$value = $array;
			for ($i = 0; $i < $count; $i++) {
				$key = trim($matches[0][$i], '[]');
				if ($key === '') {
					break;
				}

				if (isset($value[$key])) {
					$value = $value[$key];
				} else {
					return NULL;
				}
			}

		}
		else {
			return NULL;
		}

		return $value;
	}


	/**
	 * Értékek szűrése, adott típusra alakítása, default érték beállítása
	 */				
	private function _filter($filter, $value, $default_value)
	{
		// ha nincs filter, és üres a value
		if (is_null($filter) && empty($value)) {
			$value = $default_value;
		}
		// ha nincs filter és nem üres a value - alap szűrések
		elseif (is_null($filter) && !empty($value)) {
			
			// ha működik a magic_quotes_gpc (csak régi PHP-nál) hozzáadott /-ek eltávolítása
			if (get_magic_quotes_gpc() && ($this->is_post() || $this->is_get())) {
				$value = $this->_stripSlashes($value);
			}

			// html tag-ek eltávolítása 
			$value = $this->_stripTags($value);
		}

		// veszélyes html tag-ek eltávolítása pl: <script>
		elseif($filter == 'remove_danger_tags'){

			$value = $this->_strip_dangertags($value, array('script')); 
		}

		// ha a filter integer
		elseif (($filter == 'int' || $filter == 'integer') && is_string($value)) {
			$default_value = (is_null($default_value)) ? 0 : $default_value;
			$value = (empty($value)) ? $default_value : (int) $value;
		}
		// ha a filter boolean
		elseif ($filter == 'bool' || $filter == 'boolean') {
			//$value = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);	
			$value = strtolower($value);
			if (($value == 'f') || ($value == 'false') || ($value == 'no') || ($value == 'off') || !$value) {
				$value = FALSE;
			} else {
				$value = TRUE;
			}

		}

		return $value; 
	}

// ------- Szürő metódusok 

	/**
	 * Removes slashes from a value (rekurzív)
	 * 
	 * @param string|array $value  The value to strip
	 * @return string|array  The `$value` with slashes stripped
	 */
	private function _stripSlashes($value)
	{
		if (is_array($value)) {
			foreach ($value as $key => $sub_value) {
				$value[$key] = $this->_stripSlashes($sub_value);
			}
			return $value;
		}
		return stripslashes($value);
	}

	/**
	 * Eltávolítja a html tag-eket (rekurzív)
	 * 
	 * @param string|array $value  			string amiből el kell távolítani a html tag-eket
	 * @param string|array $allowed_tags  	engedélyezett html tag-ek ('<br><h1><p>')
	 * @return string|array  				The `$value` with slashes stripped
	 */
	private function _stripTags($value, $allowed_tags = null)
	{
		if (is_array($value)) {
			foreach ($value as $key => $sub_value) {
				$value[$key] = $this->_stripTags($sub_value, $allowed_tags);
			}
			return $value;
		}

		if(is_null($allowed_tags)) {
			return strip_tags($value);
		} else {
			return strip_tags($value, $allowed_tags);
		}
	}

	/**
	 * Eltávolítja a paraméterben megadott html tag-eket (rekurzív)
	 * 
	 * @param string|array $value  	string amiből el kell távolítani a html script tag-eket
	 * @param array $tags  			eltávolítandó html tag-ek
	 * @return string|array  		The `$value` with slashes stripped
	 */
	private function _strip_dangertags($value, $tags)
	{
		if (is_array($value)) {
			foreach ($value as $key => $sub_value) {
				$value[$key] = $this->_strip_dangertags($sub_value, $tags);
			}
			return $value;
		}
	    
	    foreach ($tags as $tag) {
			$value = preg_replace("~<" . $tag . "(.*?)>(.*?)</" . $tag . ">~i","", $value);
	    }

		return $value;
	}

}
?>