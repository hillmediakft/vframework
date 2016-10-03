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
        $view->add_link('js', SITE_ASSETS . 'pages/home.js');
        // lekérdezések
        // $this->view->settings = $this->home_model->get_settings();

        $view->title = 'page_metatitle';
        $view->description = 'page_metadescription';
        $view->keywords = 'page_metakeywords';

//$this->view->debug(true); 
        //$view->setLazyRender();
        $view->render('home/tpl_home');
    }

}
?>