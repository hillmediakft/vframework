<?php 
namespace System\Libs;
/**
* Service locator container
*
* Használat:
*	
*	DI::setContainer(new Container());
*	
*	$DI = DI::getContainer();
*
*
*	EXTEND használata:
*		DI::extend('storage', function ($storage, $c) {
*		    $storage->...();
*
*		    return $storage;
*		});
*
*		$parameter = 'egon';
*   	DI::extend('storage', function($storage, $c) use($parameter) {
*			$storage->valamiMetodus($parameter);
*			return $storage;
*		});
*
*/
class DI
{	
	/**
	 * A DI containert tárolja
	 */
	protected static $container;	

	private function __construct(){}
	private function __clone(){}

	/**
	 * DI container beállítása
	 *
	 * @param object $container
	 */
	public static function setContainer($container)
	{
		self::$container = $container;
	}

	/**
	 * DI container visszaadása
	 */
	public static function getContainer()
	{
		return self::$container;
	}

	/**
	 * Megosztott service regisztrálása a DI container-be
	 *
	 * @param string $key
	 * @param mixed $value	 
	 */
	public static function set($key, $value)
	{
		self::$container[$key] = $value;
	}

	/**
	 * Service vagy paraméter visszaadása a DI container-ből
	 *
	 * @param string $key
	 */
	public static function get($key)
	{
		return self::$container[$key];
	}

	/**
	 * Egyedi service regisztrálása a DI container-be
	 *
	 * @param string $key
	 * @param closure $callable
	 */
	public static function factory($key, $callable)
	{
		self::$container[$key] = self::$container->factory($callable);
	}

	/**
	 * Service vagy paraméter létezésének vizsgálata a DI container-ben
	 */
	public static function exists($key)
	{
		isset(self::$container[$key]);
	}

	/**
	 * Service vagy paraméter törlése a DI container-ből
	 */
	public static function delete($key)
	{
		unset(self::$container[$key]);
	}

	/**
	 * Fetching the Service Creation Function
	 *
	 * Visszaad egy regisztrált névtelen metódust.
	 *	
	 * @param string $key
	 * @return closure
	 */
	public static function raw($key)
	{
		return self::$container->raw($key);
	}

	/**
	 * Visszaadja az összes regisztrált paraméter és service nevét
	 */
	public static function keys()
	{
		return self::$container->keys();
	}
	
	/**
	 * Service kiterjesztése új elemekkel
	 *
	 * @param string $key
	 * @param closure $callable	 
	 */
	public static function extend($key, $callable)
	{
		self::$container->extend($key, $callable);
	}

}
?>