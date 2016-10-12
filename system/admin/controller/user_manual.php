<?php
namespace System\Admin\Controller;
use System\Core\Admin_controller;
use System\Core\View;

class User_manual extends Admin_controller {

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$view = new View();
		
		$view->title = 'Admin dokumentáció oldal';
		$view->description = 'Admin dokumentáció description';
		
		$view->add_link('js', ADMIN_JS . 'pages/common.js');
		
		$view->set_layout('tpl_layout');
		$view->render('user_manual/tpl_user_manual');
	}
}
?>