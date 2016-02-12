<?php

class Slider extends Admin_controller {

    function __construct()
    {
        parent::__construct();
        Acl::check("menu_slider", $this->request->get_httpreferer());
        $this->loadModel('slider_model');
    }

    public function index()
    {
        // adatok bevitele a view objektumba
        $this->view->title = 'Slider oldal';
        $this->view->description = 'Slider oldal description';

        $this->view->css_link[] = $this->make_link('css', ADMIN_ASSETS, 'plugins/jquery-ui/jquery-ui-1.10.3.custom.min.css');
        $this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js');
        $this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/bootbox/bootbox.min.js');
        $this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/slider.js');

        $this->view->slider = $this->slider_model->all_slides_query();

        $this->view->render('slider/tpl_slider');
    }

    /**
     * Új slide hozzáadása
     *
     * @return void
     */
    public function new_slide()
    {
        if ($this->request->has_post('submit_new_slide')) {

            $result = $this->slider_model->add_slide();
            if ($result) {
                Util::redirect('slider');
            } else {
                Util::redirect('slider/new_slide');
            }
        }

        // adatok bevitele a view objektumba
        $this->view->title = 'Új slide oldal';
        $this->view->description = 'Új slide oldal description';

        $this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/ckeditor/ckeditor.js');
        $this->view->css_link[] = $this->make_link('css', ADMIN_ASSETS, 'plugins/bootstrap-fileupload/bootstrap-fileupload.css');
        $this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/bootstrap-fileupload/bootstrap-fileupload.js');
        $this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/new_slide.js');

        $this->view->render('slider/tpl_new_slide');
    }

    /**
     * 	A slider módosítása (kép és szövegek cseréje)
     *
     * 	@param Int $this->registry->params['id']
     * 	@return void
     */
    public function edit() {
        $id = (int) $this->request->get_params('id');

        if ($this->request->has_post('submit_update_slide')) {
            $result = $this->slider_model->update_slide($id);
            if ($result) {
                Util::redirect('slider');
            }
        }
        // adatok bevitele a view objektumba
        $this->view->title = 'Slider szerkesztése oldal';
        $this->view->description = 'Slider szerkesztése description';

        $this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/bootbox/bootbox.min.js');
        $this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/ckeditor/ckeditor.js');
        $this->view->css_link[] = $this->make_link('css', ADMIN_ASSETS, 'plugins/bootstrap-fileupload/bootstrap-fileupload.css');
        $this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/bootstrap-fileupload/bootstrap-fileupload.js');
        $this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/edit_slide.js');

        $this->view->slider = $this->slider_model->one_slide_query($id);

        $this->view->render('slider/tpl_edit_slide');
    }

    /**
     * 	Slide törlése
     *
     */
    public function delete()
    {
        $id = (int) $this->request->get_params('id');
        $this->slider_model->delete_slide($id);
        Util::redirect('slider');
    }

    /**
     * A sliderek sorrendjének módosításakor meghívott action (slider/order)
     *
     * Megvizsgálja, hogy a kérés xmlhttprequest volt-e (Ajax), ha igen meghívja a slider_order() metódust 
     *
     * @return void
     */
    public function order()
    {
        if ($this->request->is_ajax()) {
            if ($this->request->has_post('action') && $this->request->get_post('action') == 'update_slider_order') {
                $this->slider_model->slider_order();
            }
        }
    }

}
?>