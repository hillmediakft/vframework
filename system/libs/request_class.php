<?php 
/**
* Request osztály
*/
class Request {

	private $uri; // uri objektum
	private $router; // router objektum
	private $filter = null; // filter objektum

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
	public function get_post($key = NULL, $filter = NULL, $default_value = '')
	{
		// adatok a $_POST tömbből
		$post_data = $this->_fetch_from_array($_POST, $key);
		
		if(!isset($this->filter)){
			// filter objektum példányosítása, ha még nincs
			$this->filter = new Filter();
		}
		$filtered_data = $this->filter->sanitize($post_data, $filter);

		if( ($filtered_data === '' || is_null($filtered_data)) && $default_value !== '' ) {
			return $default_value;
		}

		return $filtered_data;
	}

	/**
	 * Adatok visszaadása a $_GET tömbből
	 *
	 * @param string $key 	 
	 * @param string $filter	 
	 * @param string $default_value
	 * @return mixed	 
	 */
	public function get_query($key = NULL, $filter = NULL, $default_value = '')
	{
		// adatok a $_GET tömbből
		$query_data = $this->_fetch_from_array($_GET, $key);
		
		if(!isset($this->filter)){
			// filter objektum példányosítása, ha még nincs
			$this->filter = new Filter();
		}
		$filtered_data = $this->filter->sanitize($query_data, $filter);

		if( ($filtered_data === '' || is_null($filtered_data)) && $default_value !== '' ) {
			return $default_value;
		}

		return $filtered_data;
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
	 * Ha nem adunk paramétert a metódusnak, akkor azt vizsgálja, hogy üres-e a $_POST tömb
	 * (ha a $_POST tömb üres, akkor false-t ad vissza, ha nem üres, akkor true)
	 *
	 * @return boolean
	 */
	public function has_post($index = null)
	{
		if(is_null($index)){
			return !empty($_POST);
		}

		return isset($_POST[$index]);
	}

	/**
	 * Ellenőrzi, hogy létezik-e a paraméterként kapott index a $_GET szuperglobális tömbben
	 * Ha nem adunk paramétert a metódusnak, akkor azt vizsgálja, hogy üres-e a $_GET tömb
	 * (ha a $_GET tömb üres, akkor false-t ad vissza, ha nem üres, akkor true)
	 *
	 * @return boolean
	 */
	public function has_query($index = null)
	{
		if(is_null($index)){
			return !empty($_GET);
		}

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
					throw new Exception('Nincs ' . $index . ' indexu elem a $_POST, vagy $_GET es $_REQUEST tombben');
					exit();
					//return NULL;
				}
			}

		}
		else {
			throw new Exception('Nincs ' . $index . ' indexu elem a $_POST, vagy $_GET es $_REQUEST tombben');
			exit();
			//return NULL;
		}

		return $value;
	}

}
?>