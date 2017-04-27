<?php
namespace System\Admin\Controller;
use System\Core\AdminController;
use System\Core\View;
use System\Libs\DI;
use System\Libs\Auth;
use System\Libs\Config;
use System\Libs\Uploader;
use System\Libs\Message;

class Documents extends AdminController {

    function __construct()
    {
        parent::__construct();
        $this->loadModel('document_model');
    }

    public function index()
    {
        $view = new View();

        $data['title'] = 'Admin document oldal';
        $data['description'] = 'Admin document oldal description';
        $data['all_document'] = $this->document_model->findDocument();
// $view->debug();      
        $view->add_links(array('datatable', 'bootbox', 'vframework', 'documents'));
        $view->render('documents/tpl_document_list', $data);
    }

    /**
     * Dokumentum hozzáadása
     */
    public function insert()
    {
        if ($this->request->has_post()) {

            $data['title'] = $this->request->get_post('title');
            $data['description'] = $this->request->get_post('description');
            $data['category_id'] = $this->request->get_post('category_id', 'integer');

            $result = $this->document_model->insert($data);

            if ($result) {
                $this->response->redirect('admin/documents');
            } else {
                $this->response->redirect('admin/documents/insert');
            }
        }

        $this->loadModel('documentcategory_model');
        $view = new View();

        $data['title'] = 'Admin document oldal';
        $data['description'] = 'Admin document oldal description';
        $data['category_list'] = $this->documentcategory_model->selectCategories();

        $view->add_links(array('validation', 'ckeditor', 'vframework', 'kartik-bootstrap-fileinput', 'document_insert'));
        $view->render('documents/tpl_document_insert', $data);
    }

    /**
     * Dokumentum módosítása
     */
    public function update($id)
    {
        $id = (int)$id;
        
        if ($this->request->has_post()) {

            $data['title'] = $this->request->get_post('title');
            $data['description'] = $this->request->get_post('description');
            $data['category_id'] = $this->request->get_post('category_id', 'integer');

            $result = $this->document_model->update($id, $data);
            if ($result !== false) {
                $this->response->redirect('admin/documents');
            } else {
                $this->response->redirect('admin/documents/update/' . $id);
            }
        }

        $this->loadModel('documentcategory_model');
        $view = new View();

        $data['title'] = 'Admin document oldal';
        $data['description'] = 'Admin document oldal description';
        $data['category_list'] = $this->documentcategory_model->selectCategories();
        $data['document'] = $this->document_model->getDocument($id);
        // feltöltött file-ok listája
        //$data['filelist'] = $this->show_file_list_2($id);

// $view->debug(true);       
        $view->add_links(array('validation', 'ckeditor', 'vframework', 'kartik-bootstrap-fileinput', 'document_update'));
        $view->render('documents/tpl_document_update', $data);
    }

    /**
     * 	Törlése AJAX-al
     */
    public function delete_document_AJAX()
    {
        if ($this->request->is_ajax()) {
            //if (Auth::hasAccess('documents.delete_document_AJAX')) {
            if (1) {
                // a POST-ban kapott item_id egy tömb 
                $id_arr = $this->request->get_post('item_id');

                // a sikeres törlések számát tárolja
                $success_counter = 0;
                // a sikeresen törölt id-ket tartalmazó tömb
                $success_id = array();
                // a sikertelen törlések számát tárolja
                $fail_counter = 0;

                $file_helper = DI::get('file_helper');
                // dokumentumok feltöltési helye
                $upload_path = Config::get('documents.upload_path');

                // bejárjuk a $data_arr tömböt és minden elemen végrehajtjuk a törlést
                foreach ($id_arr as $id) {
                    //átalakítjuk a integer-ré a kapott adatot
                    $id = (int)$id;
                    //lekérdezzük a törlendő document képének a nevét, hogy törölhessük a szerverről
                    $documents_to_delete = $this->document_model->getDocumentFiles($id);
                    //document törlése  
                    $result = $this->document_model->delete($id);

                    if ($result !== false) {
                        // ha a törlési sql parancsban nincs hiba
                        if ($result > 0) {
                            if (!empty($documents_to_delete)) {
                                foreach ($documents_to_delete as $file_name) {
                                    $file_helper->delete(array($upload_path . $file_name));
                                }
                            }
                            //sikeres törlés
                            $success_counter += $result;
                            $success_id[] = $id;
                        } else {
                            //sikertelen törlés
                            $fail_counter += 1;
                        }
                    } else {
                        // ha a törlési sql parancsban hiba van
                        $this->response->json(array(
                            'status' => 'error',
                            'message_error' => 'Hibas sql parancs: nem sikerult a DELETE lekerdezes az adatbazisbol!'
                        ));
                    }
                }

                // üzenetek visszaadása
                $respond = array();
                $respond['status'] = 'success';

                if ($success_counter > 0) {
                    $respond['message_success'] = $success_counter . ' dokumentum törölve.';
                }
                if ($fail_counter > 0) {
                    $respond['message_error'] = $fail_counter . ' dokumentot már töröltek!';
                }

                // válasz visszaadása
                $this->response->json($respond);


            } else {
                $this->response->json(array(
                    'status' => 'error',
                    'message' => 'Nincs engedélye a művelet végrehajtásához!'
                ));
            }
        }
    }

    /**
     *  (AJAX) Új lakás adatok bevitele adatbázisba,
     *  Lakás adatok módosítása az adatbázisban
     */
    public function insert_update_data_ajax()
    {
        if ($this->request->is_ajax()) {
            if ($this->request->has_post()) {

               //megadja, hogy update, vagy insert lesz
                $update_marker = false;
                //megadja, hogy insert utáni update, normál update lesz (modositas_datum megadása miatt)
                $update_real = false;

                $data = $this->request->get_post(null, 'strip_danger_tags');

                // megvizsgáljuk, hogy a post adatok között van-e update_id
                // update-nél a javasriptel hozzáadunk a post adatokhoz egy update_id elemet
                if (isset($data['update_id'])) {
                    //beállítjuk, hogy update-elni kell az adatbázist
                    $update_marker = true;
                    $id = (int) $data['update_id'];
                    unset($data['update_id']);

                    //megvizsgáljuk, hogy adatbevitelkori update, vagy "rendes" update
                    // "rendes" update-nél a javasriptel hozzáadunk a post adatokhoz egy update_status elemet is
                    if (isset($data['update_status'])) {
                        $update_real = true;
                        unset($data['update_status']);
                    }
                }

                $error_messages = array();
                $error_counter = 0;

                if (empty($data['title'])) {
                    $error_messages[] = Message::show('Nem adta meg az elnevezést!');
                    $error_counter += 1;
                }

                if ($error_counter == 0) {

                    // üres stringet tartalmazó elemek esetén az adatbázisba null érték kerül
                    foreach ($data as $key => $value) {
                        if (isset($value) && $value == '') {
                            //unset($data[$key]);
                            $data[$key] = null;
                        }
                    }

                    if ($update_marker) {
        // UPDATE
                        // az update-nél már nem kell a referens id-jét módosítani
                        unset($data['ref_id']);
                        // adatok írása a táblába
                        $result = $this->document_model->update($id, $data);

                        if ($result === 0 || $result === 1) {

                            if ($update_real) {
                                Message::set('success', 'A módosítások sikeresen elmentve!');
                            } else {
                                Message::set('success', 'Sikeres mentés!');
                            }

                            $this->response->json(array(
                                "status" => 'success',
                                "message" => ''
                            ));

                        } else {
                            Message::set('error', 'A módosítások mentése nem sikerült, próbálja újra!');
                            $this->response->json(array(
                                "status" => 'error',
                                "message" => ''
                            ));
                        }
                    } else {

        // INSERT
                        $data['created'] = time();
                        // a last insert id-t adja vissza
                        $last_id = $this->document_model->insert($data);
                        $this->response->json(array(
                            "status" => 'success',
                            "last_insert_id" => $last_id,
                            "message" => 'Az adatok bekerültek az adatbázisba.'
                        ));
                    }
                } else {
                    // visszaadja a hibaüzeneteket tartalmazó tömböt
                    $this->response->json(array(
                        "status" => 'error',
                        "error_messages" => $error_messages
                    ));
                }


            }
        } else {
            $this->response->redirect('admin/error');
        }
    }

    /**
     * Dokumentum kategóriák 
     */
    public function category()
    {
        $this->loadModel('documentcategory_model');
        $view = new View();

        $data['title'] = 'Admin document oldal';
        $data['description'] = 'Admin document oldal description';
        $data['all_document_category'] = $this->documentcategory_model->selectCategories();
        $data['category_counter'] = $this->document_model->categoryCounter();
// $view->debug(true);           
        $view->add_links(array('datatable', 'bootbox', 'vframework', 'document_category'));
        $view->render('documents/tpl_document_category', $data);
    }




    /**
     * Kategória hozzáadása és módosítása (AJAX)
     */
    public function category_insert_update()
    {
        if ($this->request->is_ajax()) {
            // az id értéke lehet null is!
            $id = $this->request->get_post('id');
            $new_name = $this->request->get_post('data');
            
            if ($new_name == '') {
                $this->response->json(array(
                    'status' => 'error',
                    'message' => 'Nem lehet üres a kategória név mező!'
                ));
            }   

            $this->loadModel('documentcategory_model');

        // kategóriák lekérdezése (annak ellenőrzéséhez, hogy már létezik-e ilyen kategória)
            $existing_categorys = $this->documentcategory_model->selectCategories();
            // bejárjuk a kategória neveket és összehasonlítjuk az új névvel (kisbetűssé alakítjuk, hogy ne számítson a nagybetű-kisbetű eltérés)
            foreach($existing_categorys as $value) {
                if(strtolower($new_name) == strtolower($value['name'])) {
                    $this->response->json(array(
                        'status' => 'error',
                        'message' => 'Már létezik ' . $value['name'] . ' kategória!'
                    ));
                }   
            } 

        //insert (ha az $id értéke null)
            if ($id == null) {
                $result = $this->documentcategory_model->insertCategory($new_name);
                
                if ($result) {
                    $this->response->json(array(
                        'status' => 'success',
                        'message' => 'Kategória hozzáadva.',
                        'inserted_id' => $result
                    ));
                }
                if ($result === false){ 
                    $this->response->json(array(
                        'status' => 'error',
                        'message' => 'Adatbázis lekérdezési hiba!'
                    ));
                }
            }
        // update
            else {
                $result = $this->documentcategory_model->updateCategory((int)$id, $new_name);

                if ($result !== false) {
                    $this->response->json(array(
                        'status' => 'success',
                        'message' => 'Kategória módosítva.'
                    ));
                } else { 
                    $this->response->json(array(
                        'status' => 'error',
                        'message' => 'Adatbázis lekérdezési hiba!'
                    ));
                }
            }
        }
    }


    /**
     *  Kategória törlése (AJAX)
     */
    public function category_delete()
    {
        if($this->request->is_ajax()){
            if(1){

                $this->loadModel('documentcategory_model');

                $id = $this->request->get_post('item_id', 'integer');

            // a sikeres törlések számát tárolja
                $success_counter = 0;
            // a sikertelen törlések számát tárolja
                $fail_counter = 0; 


                if ($this->documentcategory_model->is_deletable($id)) {
                    // kategória törlése
                    $result = $this->documentcategory_model->deleteCategory($id);
                    
                    if($result !== false) {
                        // ha a törlési sql parancsban nincs hiba
                        if($result > 0){
                            $success_counter += $result;
                        }
                        else {
                            //sikertelen törlés
                            $fail_counter++;
                        }
                    }
                    else {
                        // ha a törlési sql parancsban hiba van
                        $this->response->json(array(
                            'status' => 'error',
                            'message_error' => 'Hibas sql parancs: nem sikerult a DELETE lekerdezes az adatbazisbol!',                  
                        ));
                    }

                    // üzenetek visszaadása
                    $respond = array();
                    $respond['status'] = 'success';
                    
                    if ($success_counter > 0) {
                        $respond['message_success'] = 'Kategória törölve.';
                    }
                    if ($fail_counter > 0) {
                        $respond['message_error'] = 'A kategóriát már törölték!';
                    }

                    // respond tömb visszaadása
                    $this->response->json($respond);

                } else {
                        // ha a kategória nem törölhető
                        $this->response->json(array(
                            'status' => 'error',
                            'message' => 'A kategória nem törölhető!'                  
                        ));
                }

            } else {
                $this->response->json(array(
                    'status' => 'error',
                    'message' => 'Nincs engedélye a művelet végrehajtásához!'
                ));
            }
        }
    }


    /**
     * 	(AJAX) Dokumentum feltöltés
     */
    public function doc_upload_ajax()
    {
        if ($this->request->is_ajax()) {
            //uploadExtraData beállítás küldi
            $id = $this->request->get_post('id', 'integer');
            
            $uploaded_files = $this->request->getFiles('new_doc');

        // file feltöltése
            $upload_path = Config::get('documents.upload_path');
            $doc_names = array();

            foreach ($uploaded_files as $file_arr) {

                $fileobject = new Uploader($file_arr);
                
                $newfilename = $id . '_' . $fileobject->getSource('body') . '_' . uniqid();
                // új filenév
                //$newfilename = $id . '_' . md5(uniqid());

                $fileobject->allowed(array('application/*', 'text/*', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png'));
                $fileobject->save($upload_path, $newfilename);

                $doc_names[] = $fileobject->getDest('filename');
                    
                if ($fileobject->checkError()) {
                    $this->response->json(array(
                        'status' => 'error',
                        'message' => $fileobject->getError()
                    ));
                }
            }
            
            $fileobject->cleanTemp();


// feltöltött file nevek frissítése az adatbázisban 
            // lekérdezzük a file mezőből a file-ok neveit
            $filenames_arr = $this->document_model->getDocumentFiles($id);
            // ha már tartalmaz adatot a mező, vagy ha először kerül a mezőbe adat
            $filenames_arr = (!empty($filenames_arr)) ? array_merge($filenames_arr, $doc_names) : $doc_names;
            // visszaalakítjuk json-ra
            $data = array();
            $data['file'] = json_encode($filenames_arr);

            // beírjuk az adatbázisba
            $result = $this->document_model->update($id, $data);

            if ($result !== false) {
                $this->response->json(array('status' => 'success', 'message' => 'File feltöltése sikeres.'));
            } else {
                $this->response->json(array('status' => 'error', 'message' => 'Ismeretlen hiba!'));
            }

        }
    }

    /**
     *  (AJAX) File listát jeleníti (frissíti) meg feltöltéskor
     */
    public function show_file_list()
    {
        if ($this->request->is_ajax()) {
            // db rekord id-je
            $id = $this->request->get_post('id', 'integer');
            //file adatok lekérdezése
            $filenames = $this->document_model->getDocumentFiles($id);

            // lista HTML generálása
            $html = '';
            $counter = 0;

            $file_location = Config::get('documents.upload_path');
            $url_helper = DI::get('url_helper');

            foreach ($filenames as $key => $filename) {
                $counter = $key + 1;
                $file_path = $url_helper->thumbPath($file_location . $filename);
                $html .= '<li id="doc_' . $counter . '" class="list-group-item"><i class="glyphicon glyphicon-file"> </i>&nbsp;' . $filename . '<button type="button" class="btn btn-xs btn-default" style="position: absolute; top:8px; right:8px;"><i class="glyphicon glyphicon-trash"></i></button></li>' . "\n\r";
            }

            // lista visszaküldése a javascriptnek
            echo $html;
        } else {
            $this->response->redirect('admin/error');
        }
    }


    public function download()
    {
        $file = $this->request->get_params('file');
        $file_path = Config::get('documents.upload_path') . $file;
        $file_helper = DI::get('file_helper');
        $file_helper->outputFile($file_path, $file);
        exit;
    }


    /**
     *  (AJAX) Kép vagy dokumentum törlése a feltöltöttek listából
     */
    public function file_delete()
    {
        if ($this->request->is_ajax()) {

            $id = $this->request->get_post('id', 'integer');
            // a kapott szorszámból kivonunk egyet, mert a képeket tartalamzó tömbben 0-tól indul a számozás
            $sort_id = ($this->request->get_post('sort_id', 'integer')) - 1;
            //$type = $this->request->get_post('type');

            // dokumentum nevek lekérdezése tömbbe
            $file_name_arr = $this->document_model->getDocumentFiles($id);
            // törlendő file neve
            $filename = $file_name_arr[$sort_id];
            // töröljük a tömbből az elemet
            unset($file_name_arr[$sort_id]);

            // ha az utolsó file-t is töröljük, akkor null értéket kell írnunk az adatbázisba
            if (empty($file_name_arr)) {
                $data['file'] = NULL;
            } else {
                // ha nem üres a tömb, akkor újraindexeljük
                $file_name_arr = array_values($file_name_arr);
                // új fájl lista átakaítása json formátumra 
                $new_file_list = json_encode($file_name_arr);

                $data['file'] = $new_file_list;
            }

            // módosított file lista beírása az adatbázisba
            $result = $this->document_model->update($id, $data);

            if ($result) {
                $doc_path = Config::get('documents.upload_path') . $filename;
                $file_helper = DI::get('file_helper');
                $file_helper->delete($doc_path);

                $message = Message::show('A file törölve!');
                $this->response->json(array(
                    'status' => 'success',
                    'message' => $message
                ));

            } else {
                $this->response->json(array('status' => 'error'));
            }


        } else {
            $this->response->redirect('admin/error');
        }
    }

}
?>