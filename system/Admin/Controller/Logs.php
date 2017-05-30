<?php
namespace System\Admin\Controller;
use System\Core\AdminController;
//use System\Libs\DI;
//use System\Libs\Message;
use System\Core\View;

class Logs extends AdminController {

    function __construct()
    {
        parent::__construct();
        $this->loadModel('logs_model');
    }

    public function index()
    {
        $data['title'] = 'Naplózás oldal';
        $data['description'] = 'Naplózás oldal description';
        $data['logs'] = $this->logs_model->findLogs();

        $view = new View();
        $view->add_links(array('datatable', 'vframework'));
        $view->add_link('js', ADMIN_JS . 'pages/logs.js');
//$view->debug(true);   
        $view->render('logs/tpl_logs');
    }
}
?>