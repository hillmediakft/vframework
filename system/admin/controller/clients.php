<?php
namespace System\Admin\Controller;
use System\Core\Admin_controller;
use System\Core\View;
use System\Libs\Auth;
use System\Libs\DI;
use System\Libs\Config;
use System\Libs\Message;

class Clients extends Admin_controller {

    function __construct()
    {
        parent::__construct();
        $this->loadModel('client_model');
    }
     
    /**
     * 	Partnerek listája
     */
    public function index()
    {
        $view = new View();
        
        $data['title'] = 'Partnereink oldal';
        $data['description'] = 'Partnereink description';
        $data['all_client'] = $this->client_model->allClient();
//$view->debug(true);
        $view->add_links(array('select2', 'datatable', 'bootbox', 'vframework', 'clients'));
        $view->render('clients/tpl_clients', $data);
    }

    /**
     *  Partner hozzáadása
     */
    public function insert()
    {
        if ( $this->request->has_post() ) {
           
            $img_url = $this->request->get_post('img_url');
            $data['name'] = $this->request->get_post('client_name');
            $data['link'] = $this->request->get_post('client_link');
            $data['photo'] = str_replace(Config::get('clientphoto.upload_path'), '', $img_url);
            $data['client_order'] = ($this->client_model->highest_order_number()) + 1;

            $error_counter = 0;
            //megnevezés ellenőrzése    
            if ($data['name'] === '') {
                $error_counter++;
                Message::set('error', 'A partner neve nem lehet üres!');
            }
            if ($img_url === '') {
                $error_counter++;
                Message::set('error', 'töltsön fel logót!');
            }
            if ($error_counter > 0) {
                $this->response->redirect('admin/clients/insert');
            }

            // új adatok az adatbázisba
            $result = $this->client_model->insert($data);

            if ($result !== false) {
                Message::set('success', 'Partner sikeresen hozzáadva.');
            } else {
                Message::set('error', 'unknown_error');
            }

            $this->response->redirect('admin/clients');
        }


        $view = new View();

        $data['title'] = 'Új partner oldal';
        $data['description'] = 'Új partner description';
//$view->debug(true);
        $view->add_links(array('select2', 'bootstrap-fileupload', 'croppic', 'vframework', 'client_insert'));
        $view->render('clients/tpl_client_insert', $data);
    }

    /**
     * 	Partner törlése AJAX
     */
    public function delete_client_AJAX()
    {
        if($this->request->is_ajax()){
            if(Auth::hasAccess('client_delete')){
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
                

                $file_helper = DI::get('file_helper');
                
                // bejárjuk a $id_arr tömböt és minden elemen végrehajtjuk a törlést
                foreach($id_arr as $id) {
                    //átalakítjuk a integer-ré a kapott adatot
                    $id = (int)$id;

                    //lekérdezzük a törlendő kép nevét, hogy törölhessük a szerverről
                    $photo_name = $this->client_model->selectPicture($id);
                    //rekord törlése  
                    $result = $this->client_model->delete($id);
                    
                    if($result !== false) {
                        // ha a törlési sql parancsban nincs hiba
                        if($result > 0){
                            //ha van feltöltött képe (az adatbázisban szerepel a file-név)
                            if(!empty($photo_name)){
                                    
                                $picture_path = Config::get('clientphoto.upload_path') . $photo_name;
                                //kép file törlése a szerverről
                                $file_helper->delete($picture_path);
                            }               
                            //sikeres törlés
                            $success_counter += $result;
                            $success_id[] = $id;
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
                            'message_error' => 'Adatbázis lekérdezési hiba!',                  
                        ));
                    }
                }

                // üzenetek visszaadása
                $respond = array();
                $respond['status'] = 'success';
                
                if ($success_counter > 0) {
                    $respond['message_success'] = 'Partner törölve.';
                }
                if ($fail_counter > 0) {
                    $respond['message_error'] = 'A partnert már töröltek!';
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
     * 	Partner módosítása
     */
    public function update()
    {
        $id = (int)$this->request->get_params('id');
        
        if ($this->request->has_post()) {

            $img_url = $this->request->get_post('img_url');
            $old_img = $this->request->get_post('old_img');
            $data['name'] = $this->request->get_post('client_name');
            $data['link'] = $this->request->get_post('client_link');

            //megnevezés ellenőrzése    
            if ($data['name'] === '') {
                Message::set('error', 'A partner neve nem lehet üres!');
                $this->response->redirect('admin/clients/update/' . $id);
            }

            if ($img_url !== '') {
                // új képet töltöttünk fel
                $data['photo'] = str_replace(Config::get('clientphoto.upload_path'), '', $img_url);
            } else {
                // nincs úf feltöltött kép
                $img_url = false;
            }

            // új adatok az adatbázisba
            $result = $this->client_model->update($id, $data);

            if ($result !== false) {
                // ha létezik-e új feltöltött kép, akkor törüljük a régi képet
                if ($img_url !== false) {
                    DI::get('file_helper')->delete($old_img);
                }
                Message::set('success', 'Partner adatai módosítva!');
            } else {
                Message::set('error', 'unknown_error');
            }
        
            $this->response->redirect('admin/clients');
        }


        $view = new View();

        $data['title'] = 'Partner módosítása oldal';
        $data['description'] = 'Partner módosítása description';
        $data['client'] = $this->client_model->oneClient($this->request->get_params('id'));

        $view->add_links(array('bootstrap-fileupload', 'croppic', 'vframework', 'client_update'));
        $view->render('clients/tpl_client_update', $data);
    }

    /**
     * A partnerek sorrendjének módosítása
     */
    public function order()
    {
        if ($this->request->is_ajax()) {
            $order = $this->request->get_post('order');

            // átalakítjuk a stringet tömbre  (id=sorszám&id=sorszám....)
            parse_str($order, $order_array);
            foreach ($order_array as $id => $new_order) {
                $this->client_model->order($id, $new_order);
            }
            $this->response->json(array(
                'status' => 'success'
            ));
        }
    }

    /**
     * 	A felhasználó képét tölti fel a szerverre, és készít egy kisebb méretű képet is.
     *
     * 	Ez a metódus kettő XHR kérést dolgoz fel.
     * 	Meghívásakor kap egy id nevű paramétert melynek értékei upload vagy crop
     * 		upload paraméterrel meghívva: feltölti a kiválasztott képet
     * 		crop paraméterrel meghívva: megvágja az eredeti képet és feltölti	
     * 	(a paraméterek megadása a new_user.js fájlban található: admin/users/user_img_upload/upload vagy admin/user_img_upload/crop)
     *
     * 	Az user_img_upload() model metódus JSON adatot ad vissza (ezt "echo-za" vissza ez a metódus a kérelmező javascriptnek). 
     */
    public function client_img_upload()
    {
        if ($this->request->is_ajax()) {
        
            // feltöltés helye
            $imagePath = Config::get('clientphoto.upload_path');

            if ($this->request->get_params('id') == 'upload') {
                //képkezelő objektum létrehozása (a kép a szerveren a tmp könyvtárba kerül) 
                $upload = new \System\Libs\Uploader($this->request->getFiles('img'));

                $args = array(
                    'file_new_name_body' => 'temp_' . uniqid(),
                    'file_overwrite' => true,
                    'allowed' => array('image/*'),
                    'image_resize' => true,
                    'image_x' => Config::get('clientphoto.width', 150),
                    'image_ratio_y' => true
                );

                $dest_file = $upload->make($imagePath, $args);

                if ($dest_file !== false) {

                    $this->response->json(array(
                        "status" => 'success',
                        "url" => $imagePath . $upload->get('file_dst_name'),
                        "width" => $upload->get('image_dst_x'),
                        "height" => $upload->get('image_dst_y')
                    ));
                } else {
                    $this->response->json(array(
                        "status" => 'error',
                        "message" => $upload->getError()
                    ));
                }
            }

            // Kiválasztott kép vágása és vágott kép feltöltése
            else if ($this->request->get_params('id') == 'crop') {

                // a croppic js küldi ezeket a POST adatokat    
                $imgUrl = $this->request->get_post('imgUrl');
                // original sizes
                $imgInitW = $this->request->get_post('imgInitW');
                $imgInitH = $this->request->get_post('imgInitH');
                // resized sizes
                //kerekítjük az értéket, mert lebegőpotos számot is kaphatunk és ez hibát okozna a kép generálásakor
                $imgW = round($this->request->get_post('imgW'));
                $imgH = round($this->request->get_post('imgH'));
                // offsets
                // megadja, hogy mennyit kell vágni a kép felső oldalából
                $imgY1 = $this->request->get_post('imgY1');
                // megadja, hogy mennyit kell vágni a kép bal oldalából
                $imgX1 = $this->request->get_post('imgX1');
                // crop box
                $cropW = $this->request->get_post('cropW');
                $cropH = $this->request->get_post('cropH');
                // rotation angle
                //$angle = $this->request->get_post('rotation'];
                //a $right_crop megadja, hogy mennyit kell vágni a kép jobb oldalából
                $right_crop = ($imgW - $imgX1) - $cropW;
                //a $bottom_crop megadja, hogy mennyit kell vágni a kép aljából
                $bottom_crop = ($imgH - $imgY1) - $cropH;

                //képkezelő objektum létrehozása (a feltöltött kép elérése a paraméter) 
                $upload = new \System\Libs\Uploader($imgUrl);

                $args = array(
                    'file_new_name_body' => 'client_' . md5(uniqid()),
                    'image_resize' => true,
                    'image_x' => $imgW,
                    'image_ratio_y' => true,
                    'image_crop' => array($imgY1, $right_crop, $bottom_crop, $imgX1)
                );

                $dest_file = $upload->make($imagePath, $args);

                if ($dest_file !== false) {
                    // temp kép törlése
                    DI::get('file_helper')->delete($imgUrl);
                                        
                    $this->response->json(array(
                        "status" => 'success',
                        "url" => $imagePath . $upload->get('file_dst_name')
                    ));
                } else {
                    $this->response->json(array(
                        "status" => 'error',
                        "message" => $upload->getError()
                    ));                    
                }

            }        
        } //is_ajax
    }

}
?>