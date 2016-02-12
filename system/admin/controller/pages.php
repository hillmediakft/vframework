<?php 
class Pages extends Admin_controller {

	function __construct()
	{
		parent::__construct();
        $this->loadModel('pages_model');
	}

	public function index()
	{
		// adatok bevitele a view objektumba
		$this->view->title = 'Admin pages oldal';
		$this->view->description = 'Admin pages oldal description';
		
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/pages.js');
		
		$this->view->all_pages = $this->pages_model->all_pages();
		
		$this->view->render('pages/tpl_pages');
	}
	
	/**
	 *	Oldal adatainak módosítása
	 */
	public function edit()
	{
		$id = (int)$this->request->get_params('id');

		if($this->request->has_post('submit_update_page')) {
			
			$result = $this->pages_model->update_page($id);
			
			if($result) {
				Util::redirect('pages');
			}
			else {
				Util::redirect('pages/edit/' . $id);
			}
		}	
		
		// adatok bevitele a view objektumba
		$this->view->title = 'Oldal szerkesztése';
		$this->view->description = 'Oldal szerkesztése description';
		
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/bootbox/bootbox.min.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/ckeditor/ckeditor.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/edit_page.js');
				
		// visszadja a szerkesztendő oldal adatait egy tömbben (page_id, page_title ... stb.)
		$this->view->data_arr = $this->pages_model->page_data_query($id);
		
		$this->view->render('pages/tpl_edit_page');
	
	}
	
	
	public function content()
	{
		// adatok bevitele a view objektumba
		$this->view->title = 'Admin egyéb tartalom oldal';
		$this->view->description = 'Admin egyéb tartalom oldal description';
		
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/common.js');
		
		$this->view->render('pages/tpl_content');
	}
	
	
}
?>