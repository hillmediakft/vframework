<?php 
namespace System\Libs;
/**
* Service locator container
*/
class DI
{
	/**
	 * Ebben tárolódik a dependency injection container
	 */
	protected static $container;	
	
	private function __construct(){}
	private function __clone(){}

	/**
	 * DI container beállítása
	 */
	public static function setContainer($container)
	{
		self::$container = $container;
	}

	/**
	 * DI container visszaadása
	 */
	public static function getContainer($container)
	{
		return self::$container;
	}

	/**
	 * Service visszaadása a DI container-ből
	 */
	public static function get($key)
	{
		return self::$container[$key];
	}

	public static function exists()
	{
		isset(self::$container[$key]);
	}

	public static function unset()
	{
		unset(self::$container[$key]);
	}

}
?>