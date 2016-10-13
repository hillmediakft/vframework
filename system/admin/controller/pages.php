<?php
namespace System\Admin\Controller;
use System\Core\Admin_controller;
use System\Core\View;

class Pages extends Admin_controller {

	function __construct()
	{
		parent::__construct();
		$this->loadModel('pages_model');
	}

	public function index()
	{
		$view = new View();

		$data['title'] = 'Admin pages oldal';
		$data['description'] = 'Admin pages oldal description';
		$data['all_pages'] = $this->pages_model->allPages();
		
		$view->add_links(array('vframework', 'pages'));
		$view->render('pages/tpl_pages', $data);
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
		
		$data['title'] = 'Oldal szerkesztése';
		$data['description'] = 'Oldal szerkesztése description';
		$data['page'] = $this->pages_model->onePage($id);
		
		$view->add_links(array('bootbox', 'ckeditor', 'vframework', 'page_update'));
		$view->render('pages/tpl_page_update', $data);
	}

}
?>