<?php
class File_manager extends Admin_controller {

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->view = new View();
		
		$this->view->title = 'Fájlkezelő oldal';
		$this->view->description = 'Fájlkezelő oldal description';
		
		$this->view->add_link('css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css');
		$this->view->add_links(array('jquery-ui-custom', 'elfinder', 'filemanager'));
		
		$this->view->set_layout('tpl_layout');
		$this->view->render('file_manager/tpl_file_manager');
	}
}
?>