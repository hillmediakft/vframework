<?php
namespace System\Libs;

class Registry {
	
	/**
	 *	Ez a statikus tulajdonság megkapja a Registry objektumot
	 */
	private static $_instance;
	

		/**
		 * Regisztrált service-eket tárol 
		 */
		private $registry = array();
		
		/**
		 * Regisztrált service-eket tárol
		 */
		private $factories = array();

		/**
		 * service objektumokat tárolja
		 */
		private $instances = array();


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
	public static function getInstance()
	{
		if(!self::$_instance){
			self::$_instance = new Registry();
		}
		return self::$_instance;
	}
	
	/**
	 *	Értéket ad a vars tömbnek (MÁGIKUS METÓDUS)
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

	/**
	 *	Értéket ad a vars tömbnek
	 *
	 *	@param String	$key	
	 *	@param Mixed	$value (lehet string, integer, array, object)
	 *	@return void
	 */
	public function set($key, $value) {
		$this->vars[$key] = $value;
	}


// ---- Service continer

		/**
		 * Service regisztrálása a registry tömbbe
		 * Ezek a service-ek meghíváskor bekerülnek az instances tömbbe,
		 * és következő használatkor ugyan azt az objektumot használja (singleton)
		 */
		public function setShare($key, callable $callable)
		{
			$this->registry[$key] = $callable;
		}	

		/**
		 * Service regisztrálása a factory tömbbe
		 * Ezeket a service-ek egyediek, mindig új objektum jön létre
		 */
		public function setFactory($key, callable $callable)
		{
			$this->factories[$key] = $callable;
		}

		/**
		 * Service visszaadása
		 */
		public function get($key)
		{
			if (isset($this->factories[$key])) {
				return $this->factories[$key]();
			}

			if (!isset($this->instances[$key])) {

				if (isset($this->registry[$key])) {
					$this->instances[$key] = $this->registry[$key]();
				} else {
					throw new \Exception('Nincs \'' . $key . '\' service regisztralva!');
				}

			}

			return $this->instances[$key];
		}



} // registry class vége
?>