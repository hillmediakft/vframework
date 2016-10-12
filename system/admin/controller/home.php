<?php
namespace System\Admin\Controller;
use System\Core\Admin_controller;
use System\Core\View;

class Home extends Admin_controller {

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $view = new View();

        $view->title = 'Admin kezdő oldal';
        $view->description = 'Admin kezdő oldal description';

        $view->add_link('js', ADMIN_JS . 'pages/common.js');

        $view->set_layout('tpl_layout');
        $view->render('home/tpl_home');
    }

}
?>