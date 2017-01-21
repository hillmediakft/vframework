<?php
namespace System\Site\Controller;

use System\Core\SiteController;
use System\Core\View;

class Home extends SiteController {

    function __construct()
    {
        parent::__construct();
        $this->loadModel('home_model');
    }

    public function index()
    {
        $data['title'] = 'page_metatitle';
        $data['description'] = 'page_metadescription';
        $data['keywords'] = 'page_metakeywords';

        $view = new View();
        //$view->setLazyRender();
//$this->view->debug(true); 
        $view->add_link('js', SITE_ASSETS . 'pages/home.js');
        $view->set_layout(null);
        $view->render('home/tpl_home', $data);
    }

}
?>