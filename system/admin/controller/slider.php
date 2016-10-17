<?php
namespace System\Admin\Controller;

use System\Core\Admin_controller;
use System\Core\View;
use System\Libs\Auth;
use System\Libs\Message;
use System\Libs\Config;
use System\Libs\DI;

class Slider extends Admin_controller {

    function __construct()
    {
        parent::__construct();
        Auth::hasAccess('slider_menu', $this->request->get_httpreferer());
        $this->loadModel('slider_model');
    }

    public function index()
    {
        $view = new View();

        $data['title'] = 'Slider oldal';
        $data['description'] = 'Slider oldal description';
        $data['sliders'] = $this->slider_model->allSlides();

        $view->setHelper(array('url_helper'));
        $view->add_links(array('datatable', 'bootbox', 'vframework', 'slider'));     
        $view->render('slider/tpl_slider', $data);
    }

    /**
     * Új slide hozzáadása
     */
    public function insert()
    {
        if ($this->request->has_post()) {

           if (isset($_FILES['upload_slide_picture'])) {
                // kép feltöltése, upload_slider_picture() metódussal (visszatér a feltöltött kép elérési útjával, vagy false-al)
                $dest_image_name = $this->slider_model->upload_slider_picture($_FILES['upload_slide_picture']);

                if ($dest_image_name === false) {
                    $this->response->redirect('admin/slider/insert');
                }
            } else {
                throw new Exception('Hiba slide kep feltoltesekor: Nem letezik a \$_FILES[\'upload_slide_picture\'] elem!');
                return false;
            }

            //adatok az adatbázisba
            $data['active'] = $this->request->get_post('slider_status', 'integer');
            $data['slider_order'] = ($this->slide_highest_order_number()) + 1;
            $data['picture'] = $dest_image_name;
            //$data['target_url'] = "";
            $data['text'] = $this->request->get_post('slider_text');
            $data['title'] = $this->request->get_post('slider_title');
            $data['target_url'] = $this->request->get_post('slider_link');

            // adatbázis lekérdezés 
            $result = $this->slider_model->insert($data);

            // ha sikeres az insert visszatérési érték true
            if ($result !== false) {
                Message::set('success', 'new_slide_success');
                $this->response->redirect('admin/slider');
            } else {
                Message::set('error', 'unknown_error');
                $this->response->redirect('admin/slider/insert');
            }
        }

        $view = new View();
        
        $data['title'] = 'Új slide oldal';
        $data['description'] = 'Új slide oldal description';

        $view->add_links(array('ckeditor','bootstrap-fileupload', 'slider_insert'));
        $view->render('slider/tpl_slider_insert', $data);
    }

    /**
     * 	A slider módosítása (kép és szövegek cseréje)
     *
     */
    public function update()
    {
        $id = (int) $this->request->get_params('id');

        if ($this->request->has_post()) {

            //ha van új kép feltöltve
            if ($this->request->checkFiles('update_slide_picture')) {
                // kép feltöltése (visszatér a feltöltött kép nevével, vagy false-al)
                $dest_image = $this->_uploadPicture($this->request->getFiles('update_slide_picture'));

                if ($dest_image === false) {
                    $this->response->redirect('admin/slider/update');
                }
            }

            // adatok beállítása
            $data['active'] = $this->request->get_post('slider_status', 'integer');

            if ($dest_image) {
                $data['picture'] = $dest_image;
                // régi kép adatai (ezt használjuk a régi kép törléséhez, ha új kép lett feltöltve)
                $old_img_path = Config::get('slider.upload_path') . $this->request->get_post('old_img');
                $old_thumb_path = DI::get('url_helper')->thumbPath($old_img_path);
            }

            $data['text'] = $this->request->get_post('slider_text');
            $data['title'] = $this->request->get_post('slider_title');
            $data['target_url'] = $this->request->get_post('slider_link');

            // új adatok beírása az adatbázisba (update) a $data tömb tartalmazza a frissítendő adatokat 
            $result = $this->slider_model->update($id, $data);
            // ha sikeres az adatbázisba írás
            if ($result !== false) {
                // megvizsgáljuk, hogy létezik-e új feltöltött kép
                if ($dest_image) {
                    //régi képek törlése
                    DI::get('file_helper')->delete(array($old_img_path, $old_thumb_path));
                }
                // sikeres adatbázisba írás és kép feltöltés esetén!!!!
                Message::set('success', 'slide_update_success');
            } else {
                Message::set('error', 'unknown_error');
            }

            $this->response->redirect('admin/slider');
        }

        $view = new View();
        
        $data['title'] = 'Slider szerkesztése oldal';
        $data['description'] = 'Slider szerkesztése description';
        $data['slider'] = $this->slider_model->oneSlide($id);

        $view->add_links(array('bootbox', 'ckeditor', 'bootstrap-fileupload', 'slider_update'));
        $view->render('slider/tpl_slider_update', $data);
    }

    /**
     *  Slider törlése AJAX-al
     */
    public function delete_slider_AJAX()
    {
        if($this->request->is_ajax()){
            if(1){
                // a POST-ban kapott item_id egy tömb
                $id_arr = $this->request->get_post('item_id');
                // a sikeres törlések számát tárolja
                $success_counter = 0;
                // a sikeresen törölt id-ket tartalmazó tömb
                $success_id = array();      
                // a sikertelen törlések számát tárolja
                $fail_counter = 0; 

                $file_helper = DI::get('file_helper');
                $url_helper = DI::get('url_helper');

                // bejárjuk a $id_arr tömböt és minden elemen végrehajtjuk a törlést
                foreach($id_arr as $id) {
                    //átalakítjuk a integer-ré a kapott adatot
                    $id = (int)$id;
                    //lekérdezzük a törlendő blog képének a nevét, hogy törölhessük a szerverről
                    $photo_name = $this->slider_model->selectPicture($id);
                    //blog törlése  
                    $result = $this->slider_model->delete($id);
                    
                    if($result !== false) {
                        // ha a törlési sql parancsban nincs hiba
                        if($result > 0){
                            //ha van feltöltött képe a bloghoz (az adatbázisban szerepel a file-név)
                            if(!empty($photo_name)){
                                $picture_path = Config::get('slider.upload_path') . $photo_name;
                                $thumb_picture_path = $url_helper->thumbPath($picture_path);
                                $file_helper->delete(array($picture_path, $thumb_picture_path));
                            }               
                            //sikeres törlés
                            $success_counter += $result;
                            $success_id[] = $id;
                        }
                        else {
                            //sikertelen törlés
                            $fail_counter += 1;
                        }
                    }
                    else {
                        // ha a törlési sql parancsban hiba van
                        $this->response->json(array(
                            'status' => 'error',
                            'message_error' => 'Hibas sql parancs: nem sikerult a DELETE lekerdezes az adatbazisbol!',                  
                        ));
                    }
                }

                // üzenetek visszaadása
                $respond = array();
                $respond['status'] = 'success';
                
                if ($success_counter > 0) {
                    $respond['message_success'] = 'A slide törölve.';
                }
                if ($fail_counter > 0) {
                    $respond['message_error'] = 'A slide-ot már töröltek!';
                }

                // respond tömb visszaadása
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
        $imagePath = Config::get('slider.upload_path');
        $width = Config::get('slider.width', 1170);
        $height = Config::get('slider.height', 420);
        $thumb_width = Config::get('slider.thumb_width', 200);

        //képkezelő objektum létrehozása (a kép a szerveren a tmp könyvtárba kerül) 
        $upload = new \System\Libs\Uploader($files_array);

        $args = array(
            'allowed' => array('image/*'),
            'file_new_name_body' => "slide_" . md5(uniqid()),
            'image_resize' => true,
            'image_x' => $width,
            'image_y' => $height
        );
        $dest_image = $upload->make($imagePath, $args);

        if ($dest_image !== false) {
            // thumbnail kép készítése
            $thumb_args = array(
                'file_new_name_body' => $upload->get('file_dst_name_body'),
                'file_name_body_add' => '_thumb',
                'image_resize' => true,
                'image_x' => $thumb_width,
                'image_y' => $upload->calcHeight($thumb_width)            
            );
            $upload->make($imagePath, $thumb_args);
           
        } else {
            Message::set('error', $upload->getError());
            return false;
        }
        // ha nincs hiba visszadja a feltöltött kép nevét
        return $dest_image;
    }

}
?>