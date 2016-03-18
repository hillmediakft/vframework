<?php

class Home extends Admin_controller {

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // adatok bevitele a view objektumba
        $this->view->title = 'Admin kezdő oldal';
        $this->view->description = 'Admin kezdő oldal description';

        $this->view->add_link('js', ADMIN_JS . 'pages/common.js');

        $this->view->render('home/tpl_home', false);
    }

}
?>