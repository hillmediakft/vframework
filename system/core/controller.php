<?php
namespace System\Core;
use System\Libs\DI;
use System\Libs\Session;


class Controller {

    protected $view;
    protected $request;
    protected $response;

    function __construct()
    {
        Session::init();
        $this->request = DI::get('request');
        $this->response = DI::get('response');
    }

            /*
            public function setDi($di)
            {
                $this->di = $di;
            }
            */

    /*
     *  A model file betöltése és példányosítása a paramétertől függően.
     *
     *  @param  string  (a model file neve kiterjesztés nélkül)
     */
    public function loadModel($model)
    {
        if(!isset($this->$model)) {
            $model_path = ucfirst(APP_DIR) . '\\' . ucfirst(AREA) . '\\Model\\' . ucfirst($model);
            $this->$model = new $model_path();
        }
    }

    /*
     * 	A helper file betöltése és példányosítása a paramétertől függően.
     *
     * 	@param	string	(a helper file neve kiterjesztés nélkül)
     */
    public function loadHelper($helper)
    {
        if(!isset($this->$helper)) {
            $helper_path = ucfirst(APP_DIR) . '\\Helper\\' . ucfirst($helper);
            $this->$helper = new $helper_path();
        }
    }

/*
    public function getConfig($key)
    {
        return Config::get($key);
    }
*/

}
?>