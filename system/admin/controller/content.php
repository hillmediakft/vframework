<?php 
class Content extends Admin_controller {

	function __construct()
	{
		parent::__construct();
		$this->loadModel('content_model');

	}

	public function index()
	{
		$this->view = new View();

		$this->view->title = 'Egyéb tartalom oldal';
		$this->view->description = 'Egyéb tartalom oldal description';
		
		$this->view->add_links(array('content'));
		
		$this->view->all_content = $this->content_model->all_content();
		
		$this->view->set_layout('tpl_layout');
		$this->view->render('content/tpl_content');
	}
	
	/**
	 *	Tartalmi elemek módosítása
	 */
	public function edit()
	{
		$id = (int)$this->request->get_params('id');

		if($this->request->has_post('submit_update_content')) {
		
			$this->content_model->update_content($id);
			Util::redirect('content');
		}
		
		$this->view = new View();

		$this->view->title = 'Tartalom szerkesztése';
		$this->view->description = 'Tartalom szerkesztése description';
		
		$this->view->add_links(array('bootbox', 'ckeditor', 'edit_content'));

		// visszadja a szerkesztendő oldal adatait egy tömbben (page_id, page_title ... stb.)
		$this->view->data_arr = $this->content_model->content_data_query($id);
		
		$this->view->set_layout('tpl_layout');
		$this->view->render('content/tpl_edit_content');
	}

}
?>