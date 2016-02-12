<?php 
class Content extends Admin_controller {

	function __construct()
	{
		parent::__construct();
		$this->loadModel('content_model');
	}

	public function index()
	{
		// adatok bevitele a view objektumba
		$this->view->title = 'Egyéb tartalom oldal';
		$this->view->description = 'Egyéb tartalom oldal description';
		
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/content.js');
		
		$this->view->all_content = $this->content_model->all_content();
		
		$this->view->render('content/tpl_content');
	}
	
	/**
	 *	Tartalmi elemk módosítása
	 *
	 */
	public function edit()
	{
		$id = $this->request->get_params('id');

		if($this->request->has_post('submit_update_content')) {
		
			$result = $this->content_model->update_content($id);
			Util::redirect('content');
		}
		
		// adatok bevitele a view objektumba
		$this->view->title = 'Tartalom szerkesztése';
		$this->view->description = 'Tartalom szerkesztése description';
		
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/bootbox/bootbox.min.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/ckeditor/ckeditor.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/edit_content.js');
		
		// visszadja a szerkesztendő oldal adatait egy tömbben (page_id, page_title ... stb.)
		$this->view->data_arr = $this->content_model->content_data_query($id);
		
		$this->view->render('content/tpl_edit_content');
	
	}

}
?>
