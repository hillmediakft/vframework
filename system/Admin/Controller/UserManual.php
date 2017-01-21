<?php
namespace System\Admin\Controller;

use System\Core\AdminController;
use System\Core\View;

class UserManual extends AdminController {

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$view = new View();
		
		$data['title'] = 'Admin dokumentáció oldal';
		$data['description'] = 'Admin dokumentáció description';
		
		$view->add_link('js', ADMIN_JS . 'pages/common.js');
		$view->render('user_manual/tpl_user_manual', $data);
	}
}
?>