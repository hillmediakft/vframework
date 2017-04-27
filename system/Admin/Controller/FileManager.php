<?php
namespace System\Admin\Controller;
use System\Core\AdminController;
use System\Core\View;

class FileManager extends AdminController {

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$view = new View();
		
		$data['title'] = 'Fájlkezelő oldal';
		$data['description'] = 'Fájlkezelő oldal description';
				
		$view->add_links(array('jquery-ui-elfinder', 'elfinder', 'filemanager'));
		$view->render('file_manager/tpl_file_manager', $data);
	}
}
?>