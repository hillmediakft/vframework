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

        $view->title = 'Slider oldal';
        $view->description = 'Slider oldal description';

        $view->add_links(array('datatable', 'bootbox', 'vframework', 'slider'));     

        $view->slider = $this->slider_model->allSlides();

        $view->set_layout('tpl_layout');    
        $view->render('slider/tpl_slider');
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
        
        $view->title = 'Új slide oldal';
        $view->description = 'Új slide oldal description';

        $view->add_links(array('ckeditor','bootstrap-fileupload', 'slider_insert'));

        $view->set_layout('tpl_layout');
        $view->render('slider/tpl_slider_insert');
    }

    /**
     * 	A slider módosítása (kép és szövegek cseréje)
     *
     */
    public function update()
    {
        $id = (int) $this->request->get_params('id');

        if ($this->request->has_post()) {
           
            // új kép mutatója (a false a kezdőértéke)  
            $new_picture = false;

            //ha van új kép feltöltve
            if (isset($_FILES['update_slide_picture'])) {
                // ha a hibakód 4, akkor nem lett kijelölve feltöltendő file (vagyis nem akarunk képet módosítani)
                // ha nem 4-es a hibakód, akkor sikeres, vagy valami gond van a feltöltéssel
                if ($_FILES['update_slide_picture']['error'] != 4) {
                    $new_picture = true;

                    // kép feltöltése, upload_slider_picture() metódussal (visszatér a feltöltött kép nevével, vagy false-al)
                    $dest_image_name = $this->slider_model->upload_slider_picture($_FILES['update_slide_picture']);

                    if ($dest_image_name === false) {
                        $this->response->redirect('admin/slider/update');
                    }
                }
            } else {
                throw new Exception('Hiba slide kep modositasakor: Nem letezik a \$_FILES[\'update_slide_picture\'] elem!');
                return false;
            }

            // adatok beállítása
            $data['active'] = $this->request->get_post('slider_status', 'integer');

            if ($new_picture) {
                $data['picture'] = $dest_image_name;
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
                if ($new_picture) {
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
        
        $view->title = 'Slider szerkesztése oldal';
        $view->description = 'Slider szerkesztése description';

        $view->add_links(array('bootbox', 'ckeditor', 'bootstrap-fileupload', 'slider_update'));

        $view->slider = $this->slider_model->oneSlide($id);

        $view->set_layout('tpl_layout');
        $view->render('slider/tpl_slider_update');
    }

    /**
     *  Slider törlése AJAX-al
     */
    public function delete_slider_AJAX()
    {
        if($this->request->is_ajax()){
            if(1){
                // a POST-ban kapott user_id egy string ami egy szám vagy számok felsorolása pl.: "23" vagy "12,45,76" 
                $id_string = $this->request->get_post('item_id');

                // a sikeres törlések számát tárolja
                $success_counter = 0;
                // a sikeresen törölt id-ket tartalmazó tömb
                $success_id = array();      
                // a sikertelen törlések számát tárolja
                $fail_counter = 0; 
                // a paraméterként kapott stringből tömböt csinálunk a , karakter mentén
                $id_arr = explode(',', $id_string);
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
                                $thumb_picture_path = DI::get('url_helper')->thumbPath($picture_path);
                            
                                DI::get('file_helper')->delete(array($picture_path, $thumb_picture_path));
        /*
                                $del_result = Util::del_file($picture_path);
                                $del_thumb_result = Util::del_file($thumb_picture_path);
                            
                                //kép file törlése a szerverről (ha az Util::del_file() falsot ad vissza nem tudtuk törölni a képet... hibaüzenet)
                                if(!$del_result){
                                    Message::log('A kép nem törölhető! - ' . $picture_path);
                                }
                                //kép file törlése a szerverről (ha az Util::del_file() falsot ad vissza nem tudtuk törölni a képet... hibaüzenet)
                                if(!$del_thumb_result){
                                    Message::log('A kép nem törölhető! - ' . $thumb_picture_path);
                                }
        */
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

}
?>