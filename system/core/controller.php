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

    /**
     * A model file betöltése és példányosítása a paramétertől függően.
     *
     * @param  string  $model (a model file neve kiterjesztés nélkül)
     * @param bool $return_var
     * @return void|object
     */
    public function loadModel($model, $return_var = false)
    {
        $model_path = ucfirst(APP_DIR) . '\\' . ucfirst(AREA) . '\\Model\\' . ucfirst($model);

        if (!$return_var) {
            if(!isset($this->$model)) {
                $this->$model = new $model_path();
            }
        } else {
            return new $model_path();
        }
        
    }

    /**
     * A helper file betöltése és példányosítása a paramétertől függően.
     *
     * @param string $helper
     * @param bool $return_var
     * @return void|object     
     */
    public function loadHelper($helper, $return_var = false)
    {
        $helper_class = ucfirst(APP_DIR) . '\\Helper\\' . ucfirst($helper);
    
        if (!$return_var) {
            //$helper = $helper . '_helper';
            if(!isset($this->$helper)) {
                $this->$helper = new $helper_class();
            }
        } else {
            return new $helper_class();
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