<?php
class User_manual extends Admin_controller {

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		Auth::handleLogin();

		// adatok bevitele a view objektumba
		$this->view->title = 'Admin dokumentáció oldal';
		$this->view->description = 'Admin dookumentáció description';
		
		
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/common.js');
//		$this->view->css = '';
//		$this->view->js = '';
		
		
		$this->view->render('user_manual/tpl_user_manual');
	}
}
?>