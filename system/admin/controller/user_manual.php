<?php
class User_manual extends Admin_controller {

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->view = new View();
		
		$this->view->title = 'Admin dokumentáció oldal';
		$this->view->description = 'Admin dookumentáció description';
		
		$this->view->add_link('js', ADMIN_JS . 'pages/common.js');
		
		$this->view->set_layout('tpl_layout');
		$this->view->render('user_manual/tpl_user_manual');
	}
}
?>