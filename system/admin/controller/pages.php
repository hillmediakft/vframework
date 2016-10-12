<?php
namespace System\Admin\Controller;
use System\Core\Admin_controller;
use System\Core\View;
use System\Libs\Util;

class Pages extends Admin_controller {

	function __construct()
	{
		parent::__construct();
		$this->loadModel('pages_model');
	}

	public function index()
	{
		$view = new View();

		$view->title = 'Admin pages oldal';
		$view->description = 'Admin pages oldal description';
		
		$view->add_links(array('vframework', 'pages'));
		
		$view->all_pages = $this->pages_model->allPages();
		
		$view->set_layout('tpl_layout');
		$view->render('pages/tpl_pages');
	}
	
	/**
	 *	Oldal adatainak módosítása
	 */
	public function update()
	{
		$id = (int)$this->request->get_params('id');

		if($this->request->has_post()) {
			
			$data['page_body'] = $this->request->get_post('page_body');
			$data['page_metatitle'] = $this->request->get_post('page_metatitle');
			$data['page_metadescription'] = $this->request->get_post('page_metadescription');
			$data['page_metakeywords'] = $this->request->get_post('page_metakeywords');

			// új adatok beírása az adatbázisba (update) a $data tömb tartalmazza a frissítendő adatokat 
			$result = $this->pages_model->update($id, $data);
			
			if($result !== false) {
	            Message::set('success', 'page_update_success');
				$this->response->redirect('admin/pages');
			} else {
	            Message::set('error', 'unknown_error');
				$this->response->redirect('admin/pages/update/' . $id);
			}

		}	
		
		$view = new View();
		
		$view->title = 'Oldal szerkesztése';
		$view->description = 'Oldal szerkesztése description';
		
		$view->add_links(array('bootbox', 'ckeditor', 'vframework', 'page_update'));
				
		// visszadja a szerkesztendő oldal adatait egy tömbben (page_id, page_title ... stb.)
		$view->data_arr = $this->pages_model->onePage($id);
		
		$view->set_layout('tpl_layout');
		$view->render('pages/tpl_page_update');
	}

}
?>