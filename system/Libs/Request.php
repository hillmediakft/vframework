<?php
namespace System\Libs;
use System\Libs\Filter; 

/**
* Request osztály
*/
class Request {

	// saját feltöltési hibakódok
	const UPLOAD_ERR_POST_MAX_SIZE = 101;
	const UPLOAD_ERR_NO_INDEX      = 102;

	private $uri; // uri objektum
	private $router; // router objektum
	private $filter = null; // filter objektum

	/**
	 * File feltöltési hibákat tároló tömb
	 */
	private $upload_error = array();

	public function __construct($uri, $router)
	{
		$this->uri = $uri;
		$this->router = $router;
		// útvonal átadása a routernek 
		$this->router->setCurrentUri($this->uri->get('path_full'));
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
			return $this->router->named_params;
		}
		if(array_key_exists($index, $this->router->named_params)){
			return $this->router->named_params[$index];
		} else {
			throw new \Exception('Nincs ' . $index . ' nevu elem a parameterek kozott!');
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
		return (isset($this->router->params[$index]) || isset($this->router->named_params[$index]));
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
     * 	Visszaadja a jelenlegi url-t a paraméterben megadott nyelvi kóddal módosítva
     *
     * 	@param	String	$lang_code	(nyelvi kód)
     * 	@return	String
     */
	public function url_with_language($lang_code = 'hu')
	{
		return $this->uri->get_url_with_language($lang_code);
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
	 * Adat visszaadása a $_FILES tömbből
	 */
	public function getFiles($key)
	{
		if (isset($_FILES[$key])) {
			if (is_int($_FILES[$key]['error'])) {
				return $_FILES[$key];
			}
			if (is_array($_FILES[$key]['error'])) {
				// ha a tömbelem multiple átalakítjuk single-re
				return $this->_multipleToSingle($_FILES[$key]);
			}
		} else {
			throw new \Exception('Nem letezik ' . $key . ' index a $_FILES tombben!');
		}
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
	 * Ellenőrzi, hogy létezik-e a paraméterként kapott index a $_FILES szuperglobális tömbben,
	 * Ellenőrzi, hogy a error elem értéke egyenlő-e 0-val 
	 * Ha nem adunk paramétert a metódusnak, akkor azt vizsgálja, hogy üres-e a $_FILES tömb
	 *
	 * @param string $index
	 * @return bool	 
	 */
	public function hasFiles($index)
	{
		return ( isset($_FILES[$index]) && ($_FILES[$index]['error'] === UPLOAD_ERR_OK) );			
	}

	/**
	 * Feltöltéskor keletkező hibák ellenőrzése (ha nincs feltöltve file az nem hiba!)
	 * A hibák az $upload_error tömbbe kerülnek
	 *
	 * @param string $index
	 * @return bool
	 */
	public function checkUploadError($index)
	{
		if (!empty($_SERVER['CONTENT_LENGTH']) && empty($_FILES) && empty($_POST)) {
			$this->upload_error[$index] = self::UPLOAD_ERR_POST_MAX_SIZE;
			return true;
		}
		if (!isset($_FILES[$index])) {
			$this->upload_error[$index] = self::UPLOAD_ERR_NO_INDEX;
			return true;
		}
		if ( (is_int($_FILES[$index]['error'])) && ($_FILES[$index]['error'] !== UPLOAD_ERR_OK) && ($_FILES[$index]['error'] !== UPLOAD_ERR_NO_FILE) ) {
			$this->upload_error[$index] = $_FILES[$index]['error'];
			return true;
		}
		if (is_array($_FILES[$index]['error'])) {
			$temp = array();
			foreach ($_FILES[$index]['error'] as $key => $error_code)
			{
				if ( ($error_code !== UPLOAD_ERR_OK) && ($error_code !== UPLOAD_ERR_NO_FILE) ) {
					$filename = $_FILES[$index]['name'][$key];
					$temp[$filename] = $error_code;
				}
			}
			$this->upload_error[$index] = $temp;
			return true;
		}
		// ha nincs hiba
		return false;
	}

	/**
	 * A $_FILES tömb megadott indexének 'error' eleméhez rendelt üzenetet adja vissza
	 * Multiple feltöltésnél egy tömböt ad vissza, amiben a hibásan feltöltött elemek vannak - kulcs a file neve, érték az üzenet
	 *
	 * @param string $index
	 * @return string|array
	 */
	public function getFilesError($index)
	{
		$status = array(
			UPLOAD_ERR_OK => 'uploaded_ok',
			UPLOAD_ERR_INI_SIZE => 'uploaded_too_big_ini',
			UPLOAD_ERR_FORM_SIZE => 'uploaded_too_big_html',
			UPLOAD_ERR_PARTIAL => 'uploaded_partial',
			UPLOAD_ERR_NO_FILE => 'uploaded_missing',
			UPLOAD_ERR_NO_TMP_DIR => 'uploaded_no_tmp_dir',
			UPLOAD_ERR_CANT_WRITE => 'uploaded_cant_write',
			UPLOAD_ERR_EXTENSION => 'uploaded_err_extension',
			// saját hibakódok
			self::UPLOAD_ERR_POST_MAX_SIZE => 'uploaded_too_big_post',
			self::UPLOAD_ERR_NO_INDEX => 'uploaded_no_index'
		);

		if (!empty($this->upload_error)) {

			if (is_int($this->upload_error[$index])) {
				if (array_key_exists($this->upload_error[$index], $status)) {
					return $status[$this->upload_error[$index]];
				} else {
					return 'unknown_error';
				}
			}
			if (is_array($this->upload_error[$index])) {
				$errors = array();
				foreach ($this->upload_error[$index] as $filename => $errorcode) {
					$errors[$filename] = $status[$errorcode];
				}
				return $errors;
			}
		} else {
			if ( isset($_FILES[$index]['error']) && is_int($_FILES[$index]['error']) ) {
				$code = $_FILES[$index]['error'];
				return $status[$code];
			} else {
				return 'unknown_error';
			}
		}
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
	 * @param	string	$index		Kulcs, aminek az értékét vissza kell adni az $array tömbből
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
					throw new \Exception('Nincs ' . $index . ' indexu elem a tombben');
					exit();
					//return NULL;
				}
			}

		}
		else {
			throw new \Exception('Nincs ' . $index . ' indexu elem a tombben');
			exit();
			//return NULL;
		}

		return $value;
	}

	/**
	 * Tömb átlakítása pl. $_FILES tömb esetén multiple változatról single verzióra
	 * @param array $files_array - $FILES['upload_files']
	 */
	private function _multipleToSingle($files_array)
	{
		$files = array();
		foreach ($files_array as $k => $l) {
			foreach ($l as $i => $v) {
				if (!array_key_exists($i, $files))
				$files[$i] = array();
				$files[$i][$k] = $v;
			}
		}
		return $files;
	}

}
?>