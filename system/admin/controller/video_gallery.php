<?php
class Video_gallery extends Admin_controller {

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		// adatok bevitele a view objektumba
		$this->view->title = 'Videógalériák oldal';
		$this->view->description = 'Videógalériák oldal description';
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/common.js');
		
        $this->view->render('video_gallery/tpl_video_gallery');
	}
}
?>