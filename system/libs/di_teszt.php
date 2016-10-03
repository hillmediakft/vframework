<?php 
/**
* Dependency injection container
*/
class Di_teszt {

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
				throw new Exception('Nincs ' . $key . ' service regisztralva!');
			}

		}

		return $this->instances[$key];
	}	
}
?>