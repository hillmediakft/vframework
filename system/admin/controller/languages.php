<?php
namespace System\Admin\Controller;
use System\Core\Admin_controller;
use System\Core\View;

class Languages extends Admin_controller {

	function __construct()
	{
		parent::__construct();
		$this->loadModel('languages_model');
	}

	public function index()
	{
		$view = new View();
		
		$data['title'] = 'Nyelvek oldal';
		$data['description'] = 'Nyelvek oldal description';
		$data['languages'] = $this->languages_model->get_language_data();
		
		$view->add_links(array('bootstrap-editable'));
		$view->add_link('js', ADMIN_JS . 'pages/languages.js');
		$view->render('languages/tpl_languages', $data);
	}
	
	/**
	 * AJAX request
	 * @return void
	 */
	public function save()
	{
		if($this->request->is_ajax()) {
			if ($this->request->has_post('name')) {
			
				$text_code = $this->request->get_post('name');
				$id = $this->request->get_post('pk');
				$text = $this->request->get_post('value');

				$lang = substr($text_code, -2);
				$column = 'text_' . $lang;
				
				if(!empty($text)) {

					$result = $this->languages_model->updateLang($id, $column, $text);

					if ($result !== false){
						$this->response->json(array(
							'success' => true
						));				
						//echo '{"success": true}';
					} else {
						$this->response->json(array(
							'success' => false,
							'msg' => "Szerver hiba!!"
						));
						//echo '{"success": false, "msg": "Szerver hiba!!"}';
					}
				}
				else {
					header('HTTP 400 Bad Request', true, 400);
					echo "Írjon be szöveget!";	
				}
			}
		}
	}
}
?>