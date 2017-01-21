<?php
namespace System\Admin\Controller;

use System\Core\AdminController;
use System\Core\View;

class Translations extends AdminController {

    function __construct()
    {
        parent::__construct();
        $this->loadModel('translations_model');
    }

    public function index()
    {
        $view = new View();

        $data['title'] = 'Fordítások oldal';
        $data['description'] = 'Fordítások oldal description';
        $data['translations'] = $this->translations_model->get_translations();
        
        $view->add_links(array('bootstrap-editable'));
        $view->add_link('css', ADMIN_ASSETS . 'plugins/bootstrap-editable/inputs-ext/wysihtml5/bootstrap-wysihtml5-0.0.2/bootstrap-wysihtml5-0.0.2.css');
        $view->add_link('js', ADMIN_ASSETS . 'plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js');
        $view->add_link('js', ADMIN_ASSETS . 'plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js');
        $view->add_link('js', ADMIN_ASSETS . 'plugins/bootstrap-editable/inputs-ext/wysihtml5/wysihtml5.js');
        $view->add_link('js', ADMIN_JS . 'pages/translations.js');
        
        $view->render('translations/tpl_translations', $data);
    }

    /**
     * AJAX request
     * @return void
     */
    public function save()
    {
        if ($this->request->is_ajax()) {
            if ($this->request->has_post('name')) {

                $text_code = $this->request->get_post('name');
                $id = $this->request->get_post('pk');
                $text = $this->request->get_post('value');

                $lang = substr($text_code, -2);
                $column = $lang;

                if (!empty($text)) {

                    $result = $this->translations_model->updateTrans($id, $column, $text);

                    if ($result !== false) {
                        $this->response->json(array(
                            'success' => true
                        ));
                        //echo '{"success": true}';
                    } else {
                        $this->response->json(array(
                            'success' => false,
                            'msg' => "Szerver hiba!!"
                        ));
                        //echo '{"success": false, "msg": "Szerver hiba!!"}';
                    }
                } else {
                    header('HTTP 400 Bad Request', true, 400);
                    echo "Írjon be szöveget!";
                }
            }
        }
    }

}
?>