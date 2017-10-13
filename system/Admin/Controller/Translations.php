<?php
namespace System\Admin\Controller;
use System\Core\AdminController;
use System\Core\View;
use System\Libs\DI;
use System\Libs\Auth;
use System\Libs\Config;
use System\Libs\Message;

class Translations extends AdminController {

    function __construct()
    {
        parent::__construct();
        $this->loadModel('translations_model');
    }

    public function index()
    {
        //Auth::hasAccess('translations.index', $this->request->get_httpreferer());

        $data['title'] = 'Fordítások oldal';
        $data['description'] = 'Fordítások oldal description';
        
        $translations = $this->translations_model->findTranslations();
        // különböző nyelvi verziók egy tömbbe convertálása
        $translations = DI::get('arr_helper')->convertMultilanguage($translations, array('text'), 'id', 'language_code');
        // csoportosízás kategóriák szerint
        $data['translations'] = DI::get('arr_helper')->groupArrayByField($translations, 'category');

        $view = new View();
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
            
            if ($this->request->is_post()) {

                $text_code = $this->request->get_post('name');
                $id = $this->request->get_post('pk', 'integer');
                $text = $this->request->get_post('value');

                $language_code = substr($text_code, -2);

                if (!empty($text)) {

                    // ha nincs még $language_code nyelvű elem az adatbázisban, akkor insert-et kell csinálni
                    if (!$this->translations_model->_checkLangVersion('translations_content', 'translation_id', $id, $language_code)) {
                        $this->translations_model->insertContent(array(
                            'translation_id' => $id,
                            'language_code' => $language_code,
                            'text' => $text
                            ));
                    }

                    $result = $this->translations_model->updateContent($id, $language_code, $text);

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


    /**
     * 
     */
    public function insert()
    {
        if ($this->request->is_post()) {
           
            $data['code'] = $this->request->get_post('code');
            $data['category'] = $this->request->get_post('category');
            $data['editor'] = $this->request->has_post('editor') ? $this->request->get_post('editor', 'integer') : 0;

            // insert a translations táblába
            $last_insert_id = $this->translations_model->insert($data);

            if ($last_insert_id !== false) {
                // insert a translations_content táblába
                $translation_data['translation_id'] = (int)$last_insert_id;

                $langcodes = Config::get('allowed_languages');
                foreach ($langcodes as $lang) {
                    $translation_data['language_code'] = $lang;
                    $translation_data['text'] = $this->request->get_post('text_' . $lang);
                    $this->translations_model->insertContent($translation_data);
                }

                Message::set('success', 'Fordítás elem hozzáadva!');
                $this->response->redirect('admin/translations');

            } else {
                Message::set('error' , 'unknown_error');
                $this->response->redirect('admin/translations/insert');
            }
        }


        $data['title'] = 'Fordítások insert oldal';
        $data['description'] = 'Fordítások insert oldal description';

        $view = new View();
        $view->render('translations/tpl_translations_insert', $data);
    }

}
?>