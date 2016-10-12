<?php 
namespace System\Admin\Controller;
use System\Core\Admin_controller;
use System\Core\View;
use System\Libs\Message;

class Content extends Admin_controller {

	function __construct()
	{
		parent::__construct();
		$this->loadModel('content_model');

	}

	public function index()
	{
		$view = new View();

		$view->title = 'Egyéb tartalom oldal';
		$view->description = 'Egyéb tartalom oldal description';
		
		$view->add_links(array('content'));
		
		$view->all_content = $this->content_model->allContents();
		
		$view->set_layout('tpl_layout');
		$view->render('content/tpl_content');
	}
	
	/**
	 *	Tartalmi elemek módosítása
	 */
	public function edit()
	{
		$id = (int)$this->request->get_params('id');

		if($this->request->has_post('submit_update_content')) {
		
			$data['content_title'] = $this->request->get_post('content_title');
			$data['content_body'] = $this->request->get_post('content_body', 'strip_danger_tags');

			$result = $this->content_model->update($id, $data);
					
			if($result !== false)
				Message::set('success', 'page_update_success');
			} else {
				Message::set('error', 'unknown_error');
			}

			$this->response->redirect('admin/content');	
		}
		
		$view = new View();

		$view->title = 'Tartalom szerkesztése';
		$view->description = 'Tartalom szerkesztése description';
		
		$view->add_links(array('bootbox', 'ckeditor', 'edit_content'));

		// visszadja a szerkesztendő oldal adatait egy tömbben (page_id, page_title ... stb.)
		$view->data_arr = $this->content_model->selectContent($id);
		
		$view->set_layout('tpl_layout');
		$view->render('content/tpl_edit_content');
	}

}
?>