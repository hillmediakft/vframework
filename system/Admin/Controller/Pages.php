<?php
namespace System\Admin\Controller;
use System\Core\AdminController;
use System\Core\View;
use System\Libs\Message;

class Pages extends AdminController {

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
	public function update($id)
	{
		$id = (int) $id;

			if($this->request->is_post()) {
				
				$data['body_hu'] = $this->request->get_post('page_body_hu', 'strip_danger_tags');
				$data['body_en'] = $this->request->get_post('page_body_en', 'strip_danger_tags');
				$data['metatitle_hu'] = $this->request->get_post('page_metatitle_hu');
				$data['metatitle_en'] = $this->request->get_post('page_metatitle_en');
				$data['metadescription_hu'] = $this->request->get_post('page_metadescription_hu');
				$data['metadescription_en'] = $this->request->get_post('page_metadescription_en');
				$data['metakeywords_hu'] = $this->request->get_post('page_metakeywords_hu');
				$data['metakeywords_en'] = $this->request->get_post('page_metakeywords_en');

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