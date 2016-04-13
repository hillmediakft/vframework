<?php

class Controller {

    /**
     * 	A HTML oldal megjelenítést végző objektum rendelődik hozzá ehhez a tulajdonsághoz
     * 	A kontruktorban jön létre
     */
    protected $view;
    public $registry;
    public $request;

    function __construct() {
        Session::init();

        $this->registry = Registry::get_instance();
        $this->request = $this->registry->request;
    }

    /*
     * 	A model file betöltése és példányosítása a paramétertől függően.
     *
     * 	@param	string	(a model file neve kiterjesztés nélkül)
     */
    public function loadModel($model) {
        // model file elérési útja
        $file = 'system/' . $this->request->get_uri('area') . '/model/' . $model . '.php';

        // megnézzük, hogy megnyitható-e a file, ha nem kivételt dobunk.
        try {
            if (!file_exists($file)) {
                throw new Exception('A ' . $file . ' file nem nyithato meg!');
            }
        } catch (Exception $e) {
            die('<br /><strong>Hiba:</strong> ' . $e->getMessage() . '<br />');
        }

        // model file behívása
        require_once($file);

        // a file betöltődése után létrejöhet az objektum, mert a $model nevű file tartalamazza a $model nevű osztályt
        // létrejön egy új tulajdonság a controller objektumban, a neve pedig a $model változóban tárolt string lesz
        // ehhez a tulajdonsághoz rendelődik az új model objektum, aminek a neve szintén a $model változóban tárolt string
        // pl.: $this->model_users = new model_users();
        $this->$model = new $model();
    }


                    /**
                     * 	Verziőval ellátott link összeállító metódus
                     *
                     * 	@param	string	$type	a link/script típusa: css vagy js
                     * 	@param	string	$path	a link útvonala, ami állandókban van (pl.: ADMIN_CSS, SITE_ASSETS)
                     * 	@param	string	$link	a link további útvonala (pl.: plugins/data-tables/DT_bootstrap.css)
                     */
                    public function make_versioned_link($type, $path, $link) {
                        $string = '';
                        switch ($type) {
                            case 'css':
                                $string .= '<link rel="stylesheet" href="' . Util::auto_version($path . $link) . '" type="text/css" />' . "\r\n";
                                break;
                            case 'js':
                                $string .= '<script type="text/javascript" src="' . Util::auto_version($path . $link) . '"></script>' . "\r\n";
                                break;
                        }
                        return $string;
                    }

}
?>