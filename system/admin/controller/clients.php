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
        
        $view->title = 'Partnereink oldal';
        $view->description = 'Partnereink description';

        $view->add_links(array('select2', 'datatable', 'bootbox', 'vframework', 'clients'));

        $view->all_client = $this->client_model->allClient();
//$view->debug(true);
        $view->set_layout('tpl_layout');        
        $view->render('clients/tpl_clients');
    }

    /**
     *  Partner hozzáadása
     */
    public function insert()
    {
        if ( $this->request->has_post() ) {
           
            $data = $this->request->get_post();
        
            if(isset($data['submit_new_client'])){
                unset($data['submit_new_client']);
            }

            $error_counter = 0;
            //megnevezés ellenőrzése    
            if (empty($data['client_name'])) {
                $error_counter++;
                Message::set('error', 'A partner neve nem lehet üres!');
            }
            if (empty($data['img_url'])) {
                $error_counter++;
                Message::set('error', 'töltsön fel logót!');
            }

            if (isset($data['img_url']) && $data['img_url'] != '') {
                $data['client_photo'] = str_replace(Config::get('clientphoto.upload_path'), '', $data['img_url']);
            }
            unset($data['img_url']);

            $data['client_order'] = ($this->client_model->highest_order_number()) + 1;

            if ($error_counter == 0) {
                // új adatok az adatbázisba
                $result = $this->client_model->insert($data);

                if ($result !== false) {
                    Message::set('success', 'Partner sikeresen hozzáadva.');
                    $this->response->redirect('admin/clients');
                } else {
                    Message::set('error', 'unknown_error');
                    $this->response->redirect('admin/clients/insert');
                }
                
            } else {
                // nem volt minden kötelező mező kitöltve
                $this->response->redirect('admin/clients/insert');
            }
        }

        $view = new View();

        $view->title = 'Új partner oldal';
        $view->description = 'Új partner description';

        $view->add_links(array('select2', 'bootstrap-fileupload', 'croppic', 'vframework', 'client_insert'));
//$view->debug(true);
        $view->set_layout('tpl_layout');
        $view->render('clients/tpl_client_insert');
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

            $data = $this->request->get_post();
            unset($data['submit_update_client']);

            $error_counter = 0;
            //megnevezés ellenőrzése    
            if (empty($data['client_name'])) {
                $error_counter++;
                Message::set('error', 'A partner neve nem lehet üres!');
            }
            if (empty($data['client_link'])) {
                $error_counter++;
                Message::set('error', 'Adjon meg linket!');
            }

            if ($error_counter == 0) {
                if (isset($data['img_url']) && $data['img_url'] != '') {
                    // új képet töltöttünk fel
                    $data['client_photo'] = str_replace(Config::get('clientphoto.upload_path'), '', $data['img_url']);
                    $old_img = $data['old_img'];
                    $img_to_delete = true;
                } else {
                    // nincs úf feltöltött kép
                    $data['client_photo'] = str_replace(Config::get('clientphoto.upload_path'), '', $data['old_img']);
                    $img_to_delete = false;
                }
                unset($data['img_url']);
                unset($data['old_img']);

                // új adatok az adatbázisba
                $result = $this->client_model->update($id, $data);

                if ($result !== false) {
                    // megvizsgáljuk, hogy létezik-e új feltöltött kép és a régi kép, nem a default
                    if ($img_to_delete) {
                        // régi kép törlése
                        DI::get('file_helper')->delete($old_img);
                    }
                    Message::set('success', 'Partner adatai módosítva!');
                } else {
                    Message::set('error', 'unknown_error');
                }
            
                $this->response->redirect('admin/clients');
            
            } else {
                // ha valamilyen hiba volt a form adataiban
                $this->response->redirect('admin/clients/update/' . $id);
                //$this->response->redirectBack();
            }
        }

        $view = new View();

        $view->title = 'Partner módosítása oldal';
        $view->description = 'Partner módosítása description';

        $view->add_links(array('bootstrap-fileupload', 'croppic', 'vframework', 'client_update'));

        // a módosítandó kliens adatai
        $view->actual_client = $this->client_model->oneClient($this->request->get_params('id'));

        $view->set_layout('tpl_layout');
        $view->render('clients/tpl_client_update');
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
            echo $this->client_model->client_img_upload();
        }
    }

}
?>