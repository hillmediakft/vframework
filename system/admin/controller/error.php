<?php 
class Error extends Admin_controller {

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		header('HTTP/1.0 404 Not Found');
		$this->view = new View();
		$this->view->title = '404 hiba oldal';
		$this->view->description = '404 hiba oldal description';
		$this->view->add_link('js', ADMIN_JS . 'pages/common.js');
		$this->view->set_layout('tpl_layout');
		$this->view->render('error/tpl_404');
	}
	
}
?>