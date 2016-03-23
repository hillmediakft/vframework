<?php

class Home extends Admin_controller {

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->view = new View();

        $this->view->title = 'Admin kezdő oldal';
        $this->view->description = 'Admin kezdő oldal description';

        $this->view->add_link('js', ADMIN_JS . 'pages/common.js');

        $this->view->set_layout('tpl_layout');
        $this->view->render('home/tpl_home');
    }

}
?>