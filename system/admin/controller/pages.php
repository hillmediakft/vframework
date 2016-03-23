<?php 
class Pages extends Admin_controller {

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->view = new View();

		$this->view->title = 'Admin pages oldal';
		$this->view->description = 'Admin pages oldal description';
		
		$this->view->add_links(array('vframework', 'pages'));
		
		$this->view->all_pages = $this->pages_model->all_pages();
		
		$this->view->set_layout('tpl_layout');
		$this->view->render('pages/tpl_pages');
	}
	
	/**
	 *	Oldal adatainak módosítása
	 */
	public function update()
	{
		$id = (int)$this->request->get_params('id');

		if($this->request->has_post()) {
			
			$result = $this->pages_model->update_page($id);
			
			if($result) {
				Util::redirect('pages');
			}
			else {
				Util::redirect('pages/update/' . $id);
			}
		}	
		
		$this->view = new View();
		
		$this->view->title = 'Oldal szerkesztése';
		$this->view->description = 'Oldal szerkesztése description';
		
		$this->view->add_links(array('bootbox', 'ckeditor', 'vframework', 'page_update'));
				
		// visszadja a szerkesztendő oldal adatait egy tömbben (page_id, page_title ... stb.)
		$this->view->data_arr = $this->pages_model->page_data_query($id);
		
		$this->view->set_layout('tpl_layout');
		$this->view->render('pages/tpl_page_update');
	}

}
?>