<?php

class Slider extends Admin_controller {

    function __construct()
    {
        parent::__construct();
        Acl::check('menu_slider', $this->request->get_httpreferer());
    }

    public function index()
    {
        $this->view = new View();

        $this->view->title = 'Slider oldal';
        $this->view->description = 'Slider oldal description';

        $this->view->add_links(array('datatable', 'bootbox', 'vframework', 'slider'));     

        $this->view->slider = $this->slider_model->all_slides_query();

        $this->view->set_layout('tpl_layout');    
        $this->view->render('slider/tpl_slider');
    }

    /**
     * Új slide hozzáadása
     */
    public function insert()
    {
        if ($this->request->has_post()) {

            $result = $this->slider_model->insert_slide();
            if ($result) {
                Util::redirect('slider');
            } else {
                Util::redirect('slider/insert');
            }
        }

        $this->view = new View();
        
        $this->view->title = 'Új slide oldal';
        $this->view->description = 'Új slide oldal description';

        $this->view->add_links(array('ckeditor','bootstrap-fileupload', 'slider_insert'));

        $this->view->set_layout('tpl_layout');
        $this->view->render('slider/tpl_slider_insert');
    }

    /**
     * 	A slider módosítása (kép és szövegek cseréje)
     *
     * 	@param Int $this->registry->params['id']
     * 	@return void
     */
    public function update()
    {
        $id = (int) $this->request->get_params('id');

        if ($this->request->has_post()) {
            $result = $this->slider_model->update_slide($id);
            if ($result) {
                Util::redirect('slider');
            }
        }

        $this->view = new View();
        
        $this->view->title = 'Slider szerkesztése oldal';
        $this->view->description = 'Slider szerkesztése description';

        $this->view->add_links(array('bootbox', 'ckeditor', 'bootstrap-fileupload', 'slider_update'));

        $this->view->slider = $this->slider_model->one_slide_query($id);

        $this->view->set_layout('tpl_layout');
        $this->view->render('slider/tpl_slider_update');
    }

    /**
     *  Slider törlése AJAX-al
     */
    public function delete_slider_AJAX()
    {
        if($this->request->is_ajax()){
            if(1){
                // a POST-ban kapott user_id egy string ami egy szám vagy számok felsorolása pl.: "23" vagy "12,45,76" 
                $id = $this->request->get_post('item_id');
                $respond = $this->slider_model->delete_slider_AJAX($id);
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
     * Sliderek sorrendjének módosítása
     */
    public function order()
    {
        if ($this->request->is_ajax()) {
            $order = $this->request->get_post('order');
            $result = $this->slider_model->slider_order($order);
            echo json_encode($result);
        }
    } 

}
?>