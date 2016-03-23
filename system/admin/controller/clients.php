<?php
class Clients extends Admin_controller {

    function __construct()
    {
        parent::__construct();
    }
     
    /**
     * 	Partnerek listája
     */
    public function index()
    {
        $this->view = new View();
        
        $this->view->title = 'Partnereink oldal';
        $this->view->description = 'Partnereink description';

        $this->view->add_links(array('select2', 'datatable', 'bootbox', 'vframework', 'clients'));

        $this->view->all_client = $this->clients_model->all_client_query();
//$this->view->debug(true);
        $this->view->set_layout('tpl_layout');        
        $this->view->render('clients/tpl_clients');
    }

    /**
     *  Partner hozzáadása
     */
    public function insert()
    {
        if ( $this->request->has_post() ) {
            $result = $this->clients_model->insert_client();
            if ($result) {
                Util::redirect('clients');
            } else {
                Util::redirect('clients/insert');
            }
        }

        $this->view = new View();

        $this->view->title = 'Új partner oldal';
        $this->view->description = 'Új partner description';

        $this->view->add_links(array('select2', 'bootstrap-fileupload', 'croppic', 'vframework', 'client_insert'));
//$this->view->debug(true);
        $this->view->set_layout('tpl_layout');
        $this->view->render('clients/tpl_client_insert');
    }

    /**
     * 	Partner törlése AJAX
     */
    public function delete_client_AJAX()
    {
        if($this->request->is_ajax()){
            if(1){
                // a POST-ban kapott user_id egy string ami egy szám vagy számok felsorolása pl.: "23" vagy "12,45,76" 
                $id = $this->request->get_post('item_id');
                $respond = $this->clients_model->delete_client_AJAX($id);
                echo json_encode($respond);
            } else {
                echo json_encode(array(
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
        if ($this->request->has_post()) {
            $result = $this->clients_model->update_client($this->request->get_params('id'));

            if ($result) {
                Util::redirect('clients');
            } else {
                Util::redirect('clients/update_client/' . $this->request->get_params('id'));
            }
        }

        $this->view = new View();

        $this->view->title = 'Partner módosítása oldal';
        $this->view->description = 'Partner módosítása description';

        $this->view->add_links(array('bootstrap-fileupload', 'croppic', 'vframework', 'client_update'));

        // a módosítandó kliens adatai
        $this->view->actual_client = $this->clients_model->one_client_query($this->request->get_params('id'));

        $this->view->set_layout('tpl_layout');
        $this->view->render('clients/tpl_client_update');
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
            echo $this->clients_model->client_img_upload();
        }
    }

    /**
     * A partnerek sorrendjének módosítása
     */
    public function order()
    {
        if ($this->request->is_ajax()) {
            $order = $this->request->get_post('order');
            $result = $this->clients_model->order($order);
            echo json_encode($result);
        }
    }    

}
?>