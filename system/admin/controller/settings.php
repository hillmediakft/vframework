<?php
namespace System\Admin\Controller;

use System\Core\Admin_controller;
use System\Core\View;
use System\Libs\Message;

class Settings extends Admin_controller {

	function __construct()
	{
		parent::__construct();
		$this->loadModel('settings_model');
	}

	public function index()
	{
		if($this->request->has_post('submit_settings')) {

			$data['ceg'] = $this->request->get_post('setting_ceg');
			$data['cim'] = $this->request->get_post('setting_cim');
			$data['email'] = $this->request->get_post('setting_email');

			// új adatok beírása az adatbázisba (update) a $data tömb tartalmazza a frissítendő adatokat 
			$result = $this->settings_model->update(1, $data);
					
			if($result !== false) {
	            Message::set('success', 'settings_update_success');
			} else {
	            Message::set('error', 'unknown_error');
			}

			$this->response->redirect('admin/settings');
		}

		$view = new View();
		
		$view->title = 'Beállítások oldal';
		$view->description = 'Beállítások oldal description';
		
		$view->add_link('js', ADMIN_JS . 'pages/settings.js');
		
		$view->settings = $this->settings_model->get_settings();

		$view->set_layout('tpl_layout');
		$view->render('settings/tpl_settings');
	}
}
?>