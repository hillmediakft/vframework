<?php
namespace System\Admin\Controller;
use System\Core\AdminController;
use System\Core\View;
use System\Libs\DI;

class Home extends AdminController {

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = 'Admin kezdő oldal';
        $data['description'] = 'Admin kezdő oldal description';

        $view = new View();
        $view->add_link('js', ADMIN_JS . 'pages/common.js');
        $view->render('home/tpl_home', $data);
    }

}
?>