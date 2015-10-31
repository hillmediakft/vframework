<?php 
class Error extends Controller {

	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->view->render('error/404', true);
	}
	
}
?>