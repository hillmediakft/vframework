<?php
class Video_gallery extends Admin_controller {

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->view = new View();
		
		$this->view->title = 'Videógalériák oldal';
		$this->view->description = 'Videógalériák oldal description';
		
		$this->view->add_link('js', ADMIN_JS . 'pages/common.js');
		
		$this->view->set_layout('tpl_layout');
        $this->view->render('video_gallery/tpl_video_gallery');
	}
}
?>