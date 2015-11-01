<?php 
class Registry {
	
	/**
	 *	Ez a statikus tulajdonság megkapja a Registry objektumot
	 */
	private static $instance;
	
	/**
	 *	Ez a tömb tárolja a registry-be kerülő adatokat
	 */
	private $vars = array();

	// nem lehet klónozni, vagy a new kulcsszóval példányt létrehozni a registry objektumból
	private function __construct() {}
	private function __clone() {}

	/**
	 *	Visszadja a Registry objektumot
	 */
	public static function get_instance()
	{
		if(!self::$instance){
			self::$instance = new Registry();
		}
		return self::$instance;
	}
	
	/**
	 *	Értéket ad a vars tömbnek
	 *
	 *	@param String	$key	
	 *	@param Mixed	$value (lehet string, integer, array, object)
	 *	@return void
	 */
	public function __set($key, $value) {
		$this->vars[$key] = $value;
	}

	/**
	 *	Visszadja a vars tömb $key kulcshoz tartozó értékét
	 *
	 *	@param	String	$key	tömbelem kulcsa, ami az adatot tartalmazza
	 *	@return	Mixed
	 */
	public function __get($key) {
		if(isset($this->vars[$key])){
			return $this->vars[$key];
		} else {
			return null;
		}

	}
} // registry class vége
?>