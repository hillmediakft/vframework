<?php 

class Error extends Controller {

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		header('HTTP/1.0 404 Not Found');
		$this->view->title = '404 hiba oldal';
		$this->view->description = '404 hiba oldal description';
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/common.js');
		$this->view->render('error/tpl_404', false);
	}
	
}
?>