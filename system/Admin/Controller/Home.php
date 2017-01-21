<?php
namespace System\Admin\Controller;
use System\Core\AdminController;
use System\Core\View;

class Home extends AdminController {

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $view = new View();

        $data['title'] = 'Admin kezdő oldal';
        $data['description'] = 'Admin kezdő oldal description';

        $view->add_link('js', ADMIN_JS . 'pages/common.js');
        $view->render('home/tpl_home', $data);
    }

}
?>