<?php
namespace System\Admin\Controller;
use System\Core\Admin_controller;
use System\Core\View;

class File_manager extends Admin_controller {

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$view = new View();
		
		$view->title = 'Fájlkezelő oldal';
		$view->description = 'Fájlkezelő oldal description';
		
		$view->add_link('css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css');
		$view->add_links(array('jquery-ui-custom', 'elfinder', 'filemanager'));
		
		$view->set_layout('tpl_layout');
		$view->render('file_manager/tpl_file_manager');
	}
}
?>