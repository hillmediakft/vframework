<?php
class Settings extends Admin_controller {

	function __construct()
	{
		parent::__construct();
		$this->loadModel('settings_model');
	}

	public function index()
	{
		if($this->request->has_post('submit_settings')) {
			$result = $this->settings_model->update_settings();
			Util::redirect('settings');
		}
		
		$this->view->settings = $this->settings_model->get_settings();

		// adatok bevitele a view objektumba
		$this->view->title = 'Beállítások oldal';
		$this->view->description = 'Beállítások oldal description';
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/settings.js');
		$this->view->render('settings/tpl_settings');
	}
}
?>