<?php
namespace System\Admin\Controller;
use System\Core\AdminController;
use System\Core\View;
use System\Libs\Auth;
use System\Libs\DI;
use System\Libs\Config;
use System\Libs\Message;
use System\Libs\Uploader;

class Client extends AdminController {

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
        Auth::hasAccess('client.index', $this->request->get_httpreferer());

        $view = new View();
        
        $data['title'] = 'Partnereink oldal';
        $data['description'] = 'Partnereink description';
        $data['all_client'] = $this->client_model->allClient();
//$view->debug(true);
        $view->add_links(array('select2', 'datatable', 'bootbox', 'vframework'));
        $view->add_link('js', ADMIN_JS . 'pages/clients.js');
        $view->render('clients/tpl_clients', $data);
    }

    /**
     *  Partner hozzáadása
     */
    public function insert()
    {
        Auth::hasAccess('client.insert', $this->request->get_httpreferer());

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
        $view->add_links(array('select2', 'bootstrap-fileupload', 'croppic', 'vframework'));
        $view->add_link('js', ADMIN_JS . 'pages/client_insert.js');
        $view->render('clients/tpl_client_insert', $data);
    }

    /**
     * 	Partner törlése AJAX
     */
    public function delete()
    {
        if($this->request->is_ajax()){
            
            if(!Auth::hasAccess('client.delete')){
                $this->response->json(array(
                    'status' => 'error',
                    'message' => 'Nincs engedélye a művelet végrehajtásához!'
                ));
            }

            // a POST-ban kapott item_id egy tömb
            $id_arr = $this->request->get_post('item_id');
            // a sikeres törlések számát tárolja
            $success_counter = 0;
            // file helper példányosítás
            $file_helper = DI::get('file_helper');
            
            foreach($id_arr as $id) {
                //átalakítjuk a integer-ré a kapott adatot
                $id = (int)$id;
                //lekérdezzük a törlendő kép nevét, hogy törölhessük a szerverről
                $photo_name = $this->client_model->selectPicture($id);
                //rekord törlése  
                $result = $this->client_model->delete($id);
                
                if($result !== false) {
                    //ha van feltöltött képe (az adatbázisban szerepel a file-név)
                    if(!empty($photo_name)){
                        //kép file törlése a szerverről
                        $file_helper->delete(Config::get('clientphoto.upload_path') . $photo_name);
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

            // siker üzenet visszaadása
            $this->response->json(array(
                'status' => 'success',
                'message' => 'Partner törölve.',                  
            ));

        } else {
            $this->response->redirect('admin/error');
        }
    }

    /**
     * 	Partner módosítása
     */
    public function update($id)
    {
        Auth::hasAccess('client.update', $this->request->get_httpreferer());        

        $id = (int)$id;
        
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
        $data['client'] = $this->client_model->oneClient($id);

        $view->add_links(array('bootstrap-fileupload', 'croppic', 'vframework'));
        $view->add_link('js', ADMIN_JS . 'pages/client_update.js');
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
     */
    public function client_img_upload()
    {
        if ($this->request->is_ajax()) {
       
            // feltöltés helye
            $upload_path = Config::get('clientphoto.upload_path');

            if ($this->request->has_params('upload')) {
                //képkezelő objektum létrehozása (a kép a szerveren a tmp könyvtárba kerül) 
                $image = new Uploader($this->request->getFiles('img'));
                $tempfilename = 'temp_' . uniqid();
                $width = Config::get('clientphoto.width', 150);

                $image->allowed(array('image/*'));
                $image->resize($width, null);
                $image->save($upload_path, $tempfilename);
 
                if ($image->checkError()) {
                    $this->response->json(array(
                        "status" => 'error',
                        "message" => $image->getError()
                    ));
                } else {
                    $this->response->json(array(
                        "status" => 'success',
                        "url" => $upload_path . $image->getDest('filename'),
                        "width" => $image->getDest('width'),
                        "height" => $image->getDest('height')
                    ));
                }
            }

            // Kiválasztott kép vágása és vágott kép feltöltése
            else if ($this->request->has_params('crop')) {

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
                $top_crop = $this->request->get_post('imgY1');
                // megadja, hogy mennyit kell vágni a kép bal oldalából
                $left_crop = $this->request->get_post('imgX1');
                // crop box
                $cropW = $this->request->get_post('cropW');
                $cropH = $this->request->get_post('cropH');
                // rotation angle
                //$angle = $this->request->get_post('rotation'];
                //a $right_crop megadja, hogy mennyit kell vágni a kép jobb oldalából
                $right_crop = ($imgW - $left_crop) - $cropW;
                //a $bottom_crop megadja, hogy mennyit kell vágni a kép aljából
                $bottom_crop = ($imgH - $top_crop) - $cropH;

                //képkezelő objektum létrehozása (a feltöltött kép elérése a paraméter) 
                $image = new Uploader($imgUrl);
                $newfilename = 'client_' . md5(uniqid());
                $image->resize($imgW, null);
                $image->crop(array($top_crop, $right_crop, $bottom_crop, $left_crop));
                $image->save($upload_path, $newfilename);

                if ($image->checkError()) {
                    $this->response->json(array(
                        "status" => 'error',
                        "message" => $image->getError()
                    ));                    
                } else {
                    // temp kép törlése
                    DI::get('file_helper')->delete($imgUrl);
                                        
                    $this->response->json(array(
                        "status" => 'success',
                        "url" => $upload_path . $image->getDest('filename')
                    ));
                }

            }        
        } //is_ajax
    }

}
?>