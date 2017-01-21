<?php
namespace System\Admin\Controller;
use System\Core\AdminController;
use System\Core\View;
use System\Libs\Message;

class Settings extends AdminController {

    function __construct() {
        parent::__construct();
        $this->loadModel('settings_model');
    }

    public function index() {
        if ($this->request->has_post('submit_settings')) {

            $data = $this->request->get_post();
            unset($data['submit_settings']);
            
            // új adatok beírása az adatbázisba (update) a $data tömb tartalmazza a frissítendő adatokat 
            $result = $this->settings_model->update(1, $data);

            if ($result !== false) {
                Message::set('success', 'settings_update_success');
            } else {
                Message::set('error', 'unknown_error');
            }

            $this->response->redirect('admin/settings');
        }

        $view = new View();

        $data['title'] = 'Beállítások oldal';
        $data['description'] = 'Beállítások oldal description';
        $data['settings'] = $this->settings_model->get_settings();

        $view->add_link('js', ADMIN_JS . 'pages/settings.js');
        $view->render('settings/tpl_settings', $data);
    }

}

?>