<?php 
/**
* Request osztály
*/
class Request {

	
	private function __construct(){}




	/**
	 * Visszadja a POST vagy GET tömbelem értékét
	 *
	 * @param string $item (a post vagy get tömbelem kulcsa)
	 * @return mixed
	 */
	public static function get_1($item){
		if (isset($_POST[$item])) {
			return $_POST[$item];	
		} else if (isset($_GET[$item])){
			return $_GET[$item];
		}
		return ''; 
	}

	public static function get($key, $cast_to = NULL, $default_value = NULL, $use_default_for_blank = FALSE)
	{
		$value = $default_value;
		
		$array_dereference = NULL;
		if (strpos($key, '[')) {
			$bracket_pos       = strpos($key, '[');
			$array_dereference = substr($key, $bracket_pos);
			$key               = substr($key, 0, $bracket_pos);
		}
		
		if (isset($_POST[$key])) {
			$value = $_POST[$key];
		} elseif (isset($_GET[$key])) {
			$value = $_GET[$key];
		}

		if ($value === '' && $use_default_for_blank && $default_value !== NULL) {
			$value = $default_value;
		}
		
		if ($array_dereference) {
			preg_match_all('#(?<=\[)[^\[\]]+(?=\])#', $array_dereference, $array_keys, PREG_SET_ORDER);
			$array_keys = array_map('current', $array_keys);
			foreach ($array_keys as $array_key) {
				if (!is_array($value) || !isset($value[$array_key])) {
					$value = $default_value;
					break;
				}
				$value = $value[$array_key];
			}
		}
		
		// This allows for data_type? casts to allow NULL through
		if ($cast_to !== NULL && substr($cast_to, -1) == '?') {
			if ($value === NULL || $value === '') {
				return NULL;
			}	
			$cast_to = substr($cast_to, 0, -1);
		}
		
		return self::cast($value, $cast_to);
	}












	/**
	 * Ellenőrzi, hogy a kérés metódusa GET volt-e
	 * 
	 * @return boolean
	 */
	public static function isGet()
	{
		return strtolower($_SERVER['REQUEST_METHOD']) == 'get';
	}
	
	
	/**
	 * Ellenőrzi, hogy a kérés metódusa POST volt-e
	 * 
	 * @return boolean
	 */
	public static function isPost()
	{
		return strtolower($_SERVER['REQUEST_METHOD']) == 'post';
	}

	/**
	 * Ellenőrzi, hogy a Ajax hívás történt-e
	 *
	 * @return boolean
	 */
	public static function is_ajax()
	{
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}




	/**
	 * Removes slashes from a value
	 * 
	 * @param string|array $value  The value to strip
	 * @return string|array  The `$value` with slashes stripped
	 */
	private static function stripSlashes($value)
	{
		if (is_array($value)) {
			foreach ($value as $key => $sub_value) {
				$value[$key] = self::stripSlashes($sub_value);
			}
			return $value;
		}
		return stripslashes($value);
	}

}
?>