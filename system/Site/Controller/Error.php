<?php 
namespace System\Site\Controller;

use System\Core\SiteController;
use System\Core\View;

class Error extends SiteController {

	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$view = new View();
		$view->set_layout(null);
		$view->render('error/404');
	}
	
}
?>