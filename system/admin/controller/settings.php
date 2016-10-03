<?php
namespace System\Admin\Controller;
use System\Core\Admin_controller;
use System\Core\View;
use System\Libs\Util;

class Settings extends Admin_controller {

	function __construct()
	{
		parent::__construct();
		$this->loadModel('settings_model');
	}

	public function index()
	{
		if($this->request->has_post('submit_settings')) {
			$this->settings_model->update_settings();
			Util::redirect('settings');
		}

		$this->view = new View();
		
		$this->view->title = 'Beállítások oldal';
		$this->view->description = 'Beállítások oldal description';
		
		$this->view->add_link('js', ADMIN_JS . 'pages/settings.js');
		
		$this->view->settings = $this->settings_model->get_settings();

		$this->view->set_layout('tpl_layout');
		$this->view->render('settings/tpl_settings');
	}
}
?>