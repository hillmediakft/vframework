<?php
namespace System\Site\Controller;
use System\Core\Site_controller;
use System\Core\View;

class Home extends Site_controller {

    function __construct()
    {
        parent::__construct();
        $this->loadModel('home_model');
    }

    public function index()
    {
        $view = new View();

        $data['title'] = 'page_metatitle';
        $data['description'] = 'page_metadescription';
        $data['keywords'] = 'page_metakeywords';

        //$view->setLazyRender();
//$this->view->debug(true); 
        $view->add_link('js', SITE_ASSETS . 'pages/home.js');
       $view->set_layout(null);
        $view->render('home/tpl_home', $data);
    }

}
?>