<?php
namespace System\Admin\Controller;
use System\Core\AdminController;
use System\Libs\DI;
use System\Libs\Message;
use System\Core\View;

class Logs extends AdminController {

    function __construct() {
        parent::__construct();
        $this->loadModel('logs_model');
    }

    public function index()
    {
        $view = new View();

        $data['title'] = 'Naplózás oldal';
        $data['description'] = 'Naplózás oldal description';
        // userek adatainak lekérdezése
        $data['logs'] = $this->logs_model->get_logs();
//$view->debug(true);   
        $view->add_links(array('datatable', 'vframework', 'logs'));
        $view->render('logs/tpl_logs');
    }
}
?>