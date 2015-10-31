<?php
class Video_gallery extends Controller {

	function __construct()
	{
		parent::__construct();
        Auth::handleLogin();
	}

	public function index()
	{
/*		Auth::handleLogin();

		if (!Acl::create()->userHasAccess('home_menu')) {
		exit('nincs hozzáférése');
		}

*/
		// adatok bevitele a view objektumba
		$this->view->title = 'Videógalériák oldal';
		$this->view->description = 'Videógalériák oldal description';
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/common.js');
		
        $this->view->render('video_gallery/tpl_video_gallery');
	}
}
?>