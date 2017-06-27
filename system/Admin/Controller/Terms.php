<?php
namespace System\Admin\Controller;
use System\Core\AdminController;
use System\Core\View;
use System\Libs\DI;
use System\Libs\Auth;

class Terms extends AdminController {

    function __construct()
    {
        parent::__construct();
        $this->loadModel('terms_model');
        //$this->loadModel('taxonomy_model');
    }

    /**
     * Címkék listája
     */
    public function index()
    {
        $data['title'] = 'címke oldal';
        $data['description'] = 'címke oldal description';
        $terms = $this->terms_model->findTerms();
        // több nyelvű adatok egy tömbbe
        $data['terms'] = DI::get('arr_helper')->convertMultilanguage($terms, array('text'), 'id', 'language_code');

        $view = new View();
        $view->add_links(array('bootbox', 'datatable', 'vframework'));
        $view->add_link('js', ADMIN_JS . 'pages/terms.js');
        $view->render('terms/tpl_terms', $data);
    }

    /**
     * Címke hozzáadása és módosítása (AJAX)
     */
    public function insert_update()
    {
        if ($this->request->is_ajax()) {

            $this->loadModel('terms_translation_model');

            // az id értéke lehet null is!
            $id = $this->request->get_post('id', 'integer');
            
            // neveket tartalmazó asszociatív tömb (hu => kategória név, en => category name)
            $new_names = $this->request->get_post('data');

            $primary_name = $new_names[LANG];
            if ($primary_name === '') {
                $this->response->json(array(
                    'status' => 'error',
                    'message' => 'Nem lehet üres az első címke mező!'
                ));
            }

        // kategóriák lekérdezése (annak ellenőrzéséhez, hogy már létezik-e ilyen kategória)
        // csak a "primary" nyelvnél nézi   
            $existing_terms = $this->terms_model->findTerms(null, LANG);

            // bejárjuk a kategória neveket és összehasonlítjuk az új névvel (kisbetűssé alakítjuk, hogy ne számítson a nagybetű-kisbetű eltérés)
            foreach($existing_terms as $value) {
                
                if (
                    // insert eset  
                    (is_null($id) && strtolower($primary_name) == strtolower($value['text'])) ||
                    // update eset
                    (!is_null($id) && $id != $value['id'] && strtolower($primary_name) == strtolower($value['text']))
                ) {
                    $this->response->json(array(
                        'status' => 'error',
                        'message' => 'Már létezik ' . $value['text'] . ' címke!'
                    ));
                }   
            } 

        //insert (ha az $id értéke null)
            if (is_null($id)) {

                // kategória létrehozása a terms táblába
                $last_insert_id = $this->terms_model->insert();
                                
                if ($last_insert_id !== false) {
                    // címke nevek beírása a terms_translation táblába
                    foreach ($new_names as $langcode => $name) {
                        $this->terms_translation_model->insertTranslation($last_insert_id, $langcode, $name);
                    }
                    
                    $this->response->json(array(
                        'status' => 'success',
                        'message' => 'Címke hozzáadva.',
                        'inserted_id' => $last_insert_id
                    ));
                } else { 
                    $this->response->json(array(
                        'status' => 'error',
                        'message' => 'Adatbázis lekérdezési hiba!'
                    ));
                }
            }
        // update
            else {
                //var_dump($new_names);die;
                // kategória nevek beírása a terms_translation táblába
                foreach ($new_names as $langcode => $name) {

                    // új nyelv utólagos hozzáadása esetén meg kell nézni, hogy van-e már $langcode nyelvi kódú elem ehhez az id-hez,
                    // mert ha nincs, akkor nem is fogja tudni update-elni, ezért update helyett insert kell                    
                    if (!$this->terms_translation_model->checkLangVersion($id, $langcode)) {
                        $translation_data['term_id'] = $id;
                        $translation_data['language_code'] = $langcode;
                        $this->terms_translation_model->insertTranslation($translation_data);
                    } else {
                        $this->terms_translation_model->updateTranslation($id, $langcode, $name);
                    }
                }

                $this->response->json(array(
                    'status' => 'success',
                    'message' => 'Címke módosítva.'
                ));

            }
        }
    }

    /**
     *  Címke törlése
     */
    public function delete()
    {
        if(!$this->request->is_ajax()){
            $this->response->redirect('admin/error');
        }

        if(!Auth::hasAccess('terms.delete')){
            $this->response->json(array(
                'status' => 'error',
                'message' => 'Nincs engedélye a művelet végrehajtásához!'
                ));
        }

        $id = $this->request->get_post('item_id', 'integer');
        $result = $this->terms_model->delete($id);
        if ($result !== false) {
            $this->response->json(array(
                'status' => 'success',
                'message' => 'Címke törölve.'
                ));            
        } else {
            // ha a törlési sql parancsban hiba van
            $this->response->json(array(
                'status' => 'error',
                'message' => 'Adatbázis lekérdezési hiba!',                  
                ));            
        }

    }

}
?>