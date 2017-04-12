<?php 
namespace System\Libs;
/**
* 
*/
class Dispatcher {

	/**
	 * Controller névtere
	 */
	private $controller_namespace;

	/**
	 * Controller neve
	 */
	private $controller;

	/**
	 * Action neve
	 */
	private $action;
	
	/**
	 * névvel ellátott paraméterek tömbje
	 */
	private $params;

    /**
     * Controller névterének megadása
     * @param string $namespace
     */
    public function setControllerNamespace($namespace)
    {
        $this->controller_namespace = $namespace;
    }

    /**
     * Closure metódusok futtatása, vagy controller objektumok példányosítása és action futtatása
     */
	public function dispatch($data_array)
	{
		foreach ($data_array as $key => $value)
		{
			if (isset($value['closure']) && is_callable($value['closure'])) {
                call_user_func_array($value['closure'], $value['params']);
			}
            elseif (isset($value['controller'])) {

				$this->controller = $value['controller'];
				$this->action = $value['action'];
				$this->params = $value['named_params'];

				$controller_class = $this->controller_namespace . ucfirst($value['controller']);

                if (class_exists($controller_class)) {
                    // first check if is a static method, directly trying to invoke it. if isn't a valid static method, we will try as a normal method invocation.
                    if (call_user_func_array(array(new $controller_class, $value['action']), $value['params']) === false) {
                        // try call the method as an non-static method. (the if does nothing, only avoids the notice)
                        if (forward_static_call_array(array($controller_class, $value['action']), $value['params']) === false) ;
                    }
                } else {
                	throw new \Exception("Nem toltheto be a: " . $controller_class . " nevu osztaly");
                }
            } else {
            	throw new \Exception("Error Processing Request");
            	
            }
		}
	}

}
?>