<?php 
namespace System\Site\Controller;
use System\Core\Site_controller;
use System\Core\View;

class Error extends Site_controller {

	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->view = new View();	
		$this->view->render('error/404');
	}
	
}
?>