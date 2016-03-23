<?php
class Languages extends Admin_controller {

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->view = new View();
		
		$this->view->title = 'Nyelvek oldal';
		$this->view->description = 'Nyelvek oldal description';
		
		$this->view->add_lins(array('bootstrap-editable'));
		$this->view->add_link('js', ADMIN_JS . 'pages/languages.js');
		
		$this->view->languages = $this->languages_model->get_language_data();
		
		$this->view->set_layout('tpl_layout');
		$this->view->render('languages/tpl_languages');
	}
	
	/**
	 * A sliderek sorrendjének módosításakor meghívott action (slider/order)
	 *
	 * Megvizsgálja, hogy a kérés xmlhttprequest volt-e (Ajax), ha igen meghívja a slider_order() metódust 
	 *
	 * @return void
	 */
	public function save()
	{
		if($this->request->is_ajax()) {
			if ($this->request->has_post('name')) {
				$this->languages_model->save_language_text();
			}
		}
	}
}
?>