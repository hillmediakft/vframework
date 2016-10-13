<?php
namespace System\Admin\Controller;
use System\Core\Admin_controller;
use System\Core\View;

class Video_gallery extends Admin_controller {

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$view = new View();
		
		$data['title'] = 'Videógalériák oldal';
		$data['description'] = 'Videógalériák oldal description';
		
		$view->add_link('js', ADMIN_JS . 'pages/common.js');
        $view->render('video_gallery/tpl_video_gallery', $data);
	}
}
?>