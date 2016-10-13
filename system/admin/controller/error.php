<?php 
namespace System\Admin\Controller;
use System\Core\Admin_controller;
use System\Core\View;

class Error extends Admin_controller {

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		header('HTTP/1.0 404 Not Found');
		$view = new View();
		$data['title'] = '404 hiba oldal';
		$data['description'] = '404 hiba oldal description';
		$view->add_link('js', ADMIN_JS . 'pages/common.js');
		$view->render('error/tpl_404', $data);
	}
	
}
?>