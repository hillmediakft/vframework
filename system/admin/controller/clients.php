<?php
class Clients extends Admin_controller {

    function __construct()
    {
        parent::__construct();
        Auth::handleLogin();
        $this->loadModel('clients_model');
    }
     
    /**
     * 	Partnerek kilistázása
     */
    public function index()
    {
        $this->view->title = 'Partnereink oldal';
        $this->view->description = 'Partnereink description';

        $this->view->css_link[] = $this->make_link('css', ADMIN_ASSETS, 'plugins/select2/select2.css');
        $this->view->css_link[] = $this->make_link('css', ADMIN_ASSETS, 'plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css');
        $this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/select2/select2.min.js');
        $this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/datatables/media/js/jquery.dataTables.min.js');
        $this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js');
        $this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/bootbox/bootbox.min.js');
        $this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'datatable.js');
        $this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/clients.js');

        $this->view->all_client = $this->clients_model->all_client_query();
//$this->view->debug(true);
        $this->view->render('clients/tpl_clients');
    }

    /**
     * 	Új partner hozzáadása
     */
    public function new_client()
    {
        if ( $this->request->has_post() ) {
            $result = $this->clients_model->insert_client();
            if ($result) {
                Util::redirect('clients');
            } else {
                Util::redirect('clients/new_client');
            }
        }

        $this->view->title = 'Új partner oldal';
        $this->view->description = 'Új partner description';
        // css linkek generálása
        $this->view->css_link[] = $this->make_link('css', ADMIN_ASSETS, 'plugins/bootstrap-fileupload/bootstrap-fileupload.css');
        $this->view->css_link[] = $this->make_link('css', ADMIN_ASSETS, 'plugins/croppic/croppic.css');
        $this->view->css_link[] = $this->make_link('css', ADMIN_ASSETS, 'plugins/select2/select2.css');
        // js linkek generálása
        $this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/select2/select2.min.js');
        $this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/croppic/croppic.min.js');
        $this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/bootstrap-fileupload/bootstrap-fileupload.js');
        $this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/new_client.js');

//$this->view->debug(true);

        $this->view->render('clients/tpl_new_client');
    }

    /**
     * 	partner törlése AJAX
     */
    public function delete_client_AJAX()
    {
        if($this->request->is_ajax()){
            $id = $this->request->get_post('client_id', 'integer');
            $result = $this->clients_model->delete_client_AJAX($id);
            echo json_encode($result);
        }
    }

    /**
     * 	Partner módosítása
     */
    public function update_client()
    {
        if ($this->request->has_post()) {
            $result = $this->clients_model->update_client($this->request->get_params('id'));

            if ($result) {
                Util::redirect('clients');
            } else {
                Util::redirect('clients/update_client/' . $this->request->get_params('id'));
            }
        }

        $this->view->title = 'Partner módosítása oldal';
        $this->view->description = 'Partner módosítása description';
        // css linkek generálása
        $this->view->css_link[] = $this->make_link('css', ADMIN_ASSETS, 'plugins/bootstrap-fileupload/bootstrap-fileupload.css');
        $this->view->css_link[] = $this->make_link('css', ADMIN_ASSETS, 'plugins/croppic/croppic.css');

        // js linkek generálása
        $this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/croppic/croppic.min.js');
        $this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/bootstrap-fileupload/bootstrap-fileupload.js');
        $this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/edit_client.js');

        // a módosítandó kolléga adatai
        $this->view->actual_client = $this->clients_model->one_client_query($this->request->get_params('id'));

        // template betöltése
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

}
?>