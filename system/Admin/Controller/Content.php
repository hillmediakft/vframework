<?php 
namespace System\Admin\Controller;
use System\Core\AdminController;
use System\Core\View;
use System\Libs\Message;

class Content extends AdminController {

	function __construct()
	{
		parent::__construct();
		$this->loadModel('content_model');

	}

	public function index()
	{
		$view = new View();

		$data['title'] = 'Egyéb tartalom oldal';
		$data['description'] = 'Egyéb tartalom oldal description';
		$data['all_content'] = $this->content_model->allContents();
		
		$view->add_links(array('content'));
		$view->render('content/tpl_content', $data);
	}
	
	/**
	 *	Tartalmi elemek módosítása
	 */
	public function edit($id)
	{
		$id = (int)$id;

		if($this->request->has_post('submit_update_content')) {
		
			$data['title'] = $this->request->get_post('content_title');
			$data['body'] = $this->request->get_post('content_body', 'strip_danger_tags');

			$result = $this->content_model->update($id, $data);
					
			if($result !== false) {
				Message::set('success', 'page_update_success');
			} else {
				Message::set('error', 'unknown_error');
			}

			$this->response->redirect('admin/content');	
		}
		
		$view = new View();

		$data['title'] = 'Tartalom szerkesztése';
		$data['description'] = 'Tartalom szerkesztése description';
		// visszadja a szerkesztendő oldal adatait egy tömbben (page_id, page_title ... stb.)
		$data['content'] = $this->content_model->selectContent($id);
		
		$view->add_links(array('bootbox', 'ckeditor', 'edit_content'));
		$view->render('content/tpl_edit_content', $data);
	}

}
?>