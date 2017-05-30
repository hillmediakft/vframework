<?php
namespace System\Admin\Controller;
use System\Core\AdminController;
use System\Core\View;
use System\Libs\Auth;
use System\Libs\Message;
use System\Libs\Config;
use System\Libs\DI;

class Slider extends AdminController {

    function __construct()
    {
        parent::__construct();
        $this->loadModel('slider_model');
        $this->loadModel('slider_translation_model');
    }

    public function index()
    {
        Auth::hasAccess('slider.index', $this->request->get_httpreferer());
        $view = new View();

        $data['title'] = 'Slider oldal';
        $data['description'] = 'Slider oldal description';
        $data['sliders'] = $this->slider_model->findSlider(null, LANG);

        $view->setHelper(array('url_helper'));
        $view->add_links(array('datatable', 'bootbox', 'vframework'));
        $view->add_link('js', ADMIN_JS . 'pages/slider.js');
        $view->render('slider/tpl_slider', $data);
    }

    /**
     * Új slide hozzáadása
     */
    public function insert()
    {
            if ($this->request->has_post()) {

                // fájl feltöltési hiba ellenőrzése
                if($this->request->checkUploadError('upload_slider_picture')){
                    Message::set('error', $this->request->getFilesError('upload_slider_picture'));
                    $this->response->redirect('admin/slider/insert');             
                }
                // ha volt feltöltés
                if ($this->request->hasFiles('upload_slider_picture')) {
                    // kép feltöltése, _uploadPicture() metódussal (visszatér a feltöltött kép elérési útjával, vagy false-al)
                    $dest_image_name = $this->_uploadPicture($this->request->getFiles('upload_slider_picture'));
                    if ($dest_image_name === false) {
                        $this->response->redirect('admin/slider/insert');
                    }
                } else {
                    Message::set('error', 'uploaded_missing');
                    $this->response->redirect('admin/slider/insert');
                }

                //adatok a slider táblába
                $data['status'] = $this->request->get_post('status', 'integer');
                $data['slider_order'] = ($this->slider_model->slide_highest_order_number()) + 1;
                $data['picture'] = $dest_image_name;
                // rekord beírása az adatbázisba
                $last_insert_id = $this->slider_model->insert($data);

                // ha sikeres az insert
                if ($last_insert_id !== false) {
                    // insert a slider_translation táblába
                    $translation_data['slider_id'] = (int)$last_insert_id;

                    $langcodes = Config::get('allowed_languages');
                    foreach ($langcodes as $lang) {
                        $translation_data['language_code'] = $lang;
                        $translation_data['target_url'] = $this->request->get_post('target_url_' . $lang);
                        $translation_data['title'] = $this->request->get_post('title_' . $lang, 'strip_danger_tags');
                        $translation_data['text'] = $this->request->get_post('text_' . $lang, 'strip_danger_tags');
                        $this->slider_translation_model->insert($translation_data);
                    }

                    Message::set('success', 'new_slide_success');
                    $this->response->redirect('admin/slider');

                } else {
                    Message::set('error', 'unknown_error');
                    $this->response->redirect('admin/slider/insert');
                }
            }


        Auth::hasAccess('slider.insert', $this->request->get_httpreferer());    
        $view = new View();
        
        $data['title'] = 'Új slide oldal';
        $data['description'] = 'Új slide oldal description';
        //$data['langs'] = Config::get('allowed_languages');

        $view->setHelper(array('html_admin_helper'));
        $view->add_links(array('ckeditor','bootstrap-fileinput'));
        $view->add_link('js', ADMIN_JS . 'pages/slider_insert.js');
        $view->render('slider/tpl_slider_insert', $data);
    }

    /**
     * 	A slider módosítása (kép és szövegek cseréje)
     *
     */
    public function update($id)
    {
        $id = (int)$id;

            if ($this->request->has_post()) {

                // fájl feltöltési hiba ellenőrzése
                if($this->request->checkUploadError('upload_slider_picture')){
                    Message::set('error', $this->request->getFilesError('upload_slider_picture'));
                    $this->response->redirect('admin/slider/update/' . $id);             
                }

                //ha van új kép feltöltve
                if ($this->request->hasFiles('upload_slider_picture')) {
                    // kép feltöltése (visszatér a feltöltött kép nevével, vagy false-al)
                    $dest_image = $this->_uploadPicture($this->request->getFiles('upload_slider_picture'));
                    if ($dest_image === false) {
                        $this->response->redirect('admin/slider/update/' . $id);
                    }
                }

                // a slider táblába kerülő adatok
                $data['status'] = $this->request->get_post('status', 'integer');

                if (isset($dest_image)) {
                    $data['picture'] = $dest_image;
                    // régi kép adatai (ezt használjuk a régi kép törléséhez, ha új kép lett feltöltve)
                    $old_img_path = Config::get('slider.upload_path') . $this->request->get_post('old_img');
                    $old_thumb_path = DI::get('url_helper')->thumbPath($old_img_path);
                }

                // update a slider táblában
                $result = $this->slider_model->update($id, $data);

                if ($result !== false) {
                    // update a slider_translation táblában
                    $langcodes = Config::get('allowed_languages');
                    foreach ($langcodes as $lang) {
                        $translation_data['target_url'] = $this->request->get_post('target_url_' . $lang);
                        $translation_data['title'] = $this->request->get_post('title_' . $lang);
                        $translation_data['text'] = $this->request->get_post('text_' . $lang, 'strip_danger_tags');
                        
                        // új nyelv hozzáadása esetén meg kell nézni, hogy van-e már $lang nyelvi kódú elem ehhez az id-hez,
                        // mert ha nincs, akkor nem is fogja tudni update-elni, ezért update helyett insert kell                    
                        if (!$this->slider_translation_model->checkLangVersion($id, $lang)) {
                            $translation_data['slider_id'] = $id; 
                            $translation_data['language_code'] = $lang; 
                            $this->slider_translation_model->insert($translation_data);
                        }
                        // ha már van ilyen nyelvi kódú elem
                        else {
                            $this->slider_translation_model->update($id, $lang, $translation_data);
                        }
                    }

                    // megvizsgáljuk, hogy létezik-e új feltöltött kép
                    if (isset($dest_image)) {
                        //régi képek törlése
                        DI::get('file_helper')->delete(array($old_img_path, $old_thumb_path));
                    }

                    Message::set('success', 'slide_update_success');
                    $this->response->redirect('admin/slider');

                } else {
                    Message::set('error', 'unknown_error');
                    $this->response->redirect('admin/slider/update/' . $id);
                }

            }


        Auth::hasAccess('slider.update', $this->request->get_httpreferer()); 
        $view = new View();
        
        $data['title'] = 'Slider szerkesztése oldal';
        $data['description'] = 'Slider szerkesztése description';
        $slider = $this->slider_model->findSlider($id);
        // adatok konvertálása az összes nyelv egy tömbbe kerül
        $slider = DI::get('arr_helper')->convertMultilanguage($slider, array('target_url', 'title', 'text'), 'id', 'language_code');
        $data['slider'] = $slider[0];
        
        $view->setHelper(array('html_admin_helper'));
        $view->add_links(array('bootbox', 'ckeditor', 'bootstrap-fileinput'));
        $view->add_link('js', ADMIN_JS . 'pages/slider_update.js');
        $view->render('slider/tpl_slider_update', $data);
    }

    /**
     *  Slider törlése AJAX-al
     */
    public function delete()
    {
        if($this->request->is_ajax()){

            if(!Auth::hasAccess('slider.delete')){
                $this->response->json(array(
                    'status' => 'error',
                    'message' => 'Nincs engedélye a művelet végrehajtásához!'
                ));
            }

            // a POST-ban kapott item_id egy tömb
            $id_arr = $this->request->get_post('item_id');
            // a sikeres törlések számát tárolja
            $success_counter = 0;

            $file_helper = DI::get('file_helper');
            $url_helper = DI::get('url_helper');

            // bejárjuk a $id_arr tömböt és minden elemen végrehajtjuk a törlést
            foreach($id_arr as $id) {
                //átalakítjuk a integer-ré a kapott adatot
                $id = (int)$id;
                //lekérdezzük a törlendő slider képének a nevét, hogy törölhessük a szerverről
                $photo_name = $this->slider_model->findPicture($id);
                //slider törlése  
                $result = $this->slider_model->delete($id);
                
                if($result !== false) {
                    //ha van feltöltött képe a sliderhoz (az adatbázisban szerepel a file-név)
                    if(!empty($photo_name)){
                        $picture_path = Config::get('slider.upload_path') . $photo_name;
                        $thumb_picture_path = $url_helper->thumbPath($picture_path);
                        $file_helper->delete(array($picture_path, $thumb_picture_path));
                    }               
                    //sikeres törlés
                    $success_counter += $result;
                }
                else {
                    // ha a törlési sql parancsban hiba van
                    $this->response->json(array(
                        'status' => 'error',
                        'message' => 'Adatbázis lekérdezési hiba!',                  
                    ));
                }
            }

            $this->response->json(array(
                'status' => 'success',
                'message' => 'A slide törölve.',                  
            ));
        }
    }

    /**
     * Sliderek sorrendjének módosítása
     */
    public function order()
    {
        if ($this->request->is_ajax()) {
            $order = $this->request->get_post('order');

            // átalakítjuk a stringet tömbre
            parse_str($order, $order_array);
            foreach ($order_array as $id => $new_order)
            {
                $this->slider_model->orderUpdate((int)$id, (int)$new_order);
            }

            $this->response->json(array('status' => 'success'));
        }
    } 


    /**
     *  Slide képet méretezi és tölti fel a szerverre (thumb képet is)
     *  (ez a metódus az update_slide() és add_slide() metódusokban hívódik meg!)
     *
     *  @param  array $files_array - $_FILES['valami']
     *  @return string (kép elérési útja) vagy false
     */
    private function _uploadPicture($files_array)
    {
        // feltöltés helye
        $upload_path = Config::get('slider.upload_path');
        $width = Config::get('slider.width', 1170);
        $height = Config::get('slider.height', 420);

        //képkezelő objektum létrehozása (a kép a szerveren a tmp könyvtárba kerül) 
        $image = new \System\Libs\Uploader($files_array);

        $filename = 'slide_' . md5(uniqid());
        $image->cropToSize($width, $height);
        $image->allowed(array('image/*'));
        $image->save($upload_path, $filename);

        $dest_image = $image->getDest('filename');

        if ($image->checkError()) {
            Message::set('error', $image->getError());
            return false;

        } else {
            // nézőkép
            $thumb_width = Config::get('slider.thumb_width', 200);
            $thumb_height = Config::get('slider.thumb_height', 72);
            $image->cropToSize($thumb_width, $thumb_height);            
            $image->save($upload_path, $filename . '_thumb');
        }

        $image->cleanTemp();

        // ha nincs hiba visszadja a feltöltött kép nevét
        return $dest_image;
    }

}
?>