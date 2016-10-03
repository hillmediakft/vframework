<?php
namespace System\Admin\Controller;
use System\Core\Admin_controller;
use System\Core\View;
use System\Libs\Util;

class Newsletter extends Admin_controller {

	function __construct()
	{
		parent::__construct();
		$this->loadModel('newsletter_model');
	}

	public function index()
	{
		$view = new View();

		$view->title = 'Hírlevél oldal';
		$view->description = 'Hírlevél oldal description';

		$view->add_links(array('datatable', 'bootbox', 'vframework', 'newsletter_eventsource'));

		$view->newsletters = $this->newsletter_model->newsletter_query();	
//$this->view->debug(true);	
		$view->set_layout('tpl_layout');
		$view->render('newsletter/tpl_newsletter');	
	}
	
	/**
	 * Hírlevél hozzáadása
	 */
	public function insert()
	{
		if($this->request->has_post()) {
			
			$data['newsletter_name'] = $this->request->get_post('newsletter_name');
			$data['newsletter_subject'] = $this->request->get_post('newsletter_subject');
			$data['newsletter_body'] = $this->request->get_post('newsletter_body');
			$data['newsletter_status'] = $this->request->get_post('newsletter_status', 'integer');
			$data['newsletter_create_date'] = date('Y-m-d-G:i');

			$result = $this->newsletter_model->insert_newsletter($data);
			
			if($result !== false) {
				Message::set('success', 'Új hírlevél hozzáadva!');
			} else {
				Message::set('error', 'unknown_error');
			}
			// Util::redirect('newsletter');
			$this->response->redirect('admin/newsletter');
		}

		$view = new View();

		$view->title = 'Hírlevél hozzáadása';
		$view->description = 'Hírlevél oldal description';
		
		$view->add_links(array('bootbox', 'ckeditor', 'vframework', 'newsletter_insert'));
		
		$view->set_layout('tpl_layout');
		$view->render('newsletter/tpl_newsletter_insert');	
	}
	
	/**
	 * Hírlevél módosítása
	 */
	public function update()
	{
		if($this->request->has_post()) {
	
			$data['newsletter_name'] = $this->request->get_post('newsletter_name');
			$data['newsletter_subject'] = $this->request->get_post('newsletter_subject');
			$data['newsletter_body'] = $this->request->get_post('newsletter_body');
			$data['newsletter_status'] = $this->request->get_post('newsletter_status', 'integer');
			$data['newsletter_create_date'] = date('Y-m-d-G:i');

			$result = $this->newsletter_model->update_newsletter((int)$this->request->get_params('id'), $data);
			
			if($result !== false) {
				Message::set('success', 'Hírlevél módosítva!');
			}
			else {
				Message::set('error', 'unknown_error');
			}
			// Util::redirect('newsletter'); 
			$this->response->redirect('admin/newsletter');
		}

		$view = new View();

		$view->title = 'Hírlevél szerkesztése';
		$view->description = 'Hírlevél oldal description';
		
		$view->add_links(array('bootbox', 'ckeditor', 'vframework', 'newsletter_update'));
		
		$view->newsletter = $this->newsletter_model->newsletter_query($this->request->get_params('id'));
	
		$view->set_layout('tpl_layout');
		$view->render('newsletter/tpl_newsletter_update');	
	}

	/**
	 *	Hírlevél törlése AJAX-al
	 */
	public function delete_newsletter_AJAX()
	{
        if($this->request->is_ajax()){
	        if(1){
	        	// a POST-ban kapott user_id egy string ami egy szám vagy számok felsorolása pl.: "23" vagy "12,45,76" 
	        	$id = $this->request->get_post('item_id');
            	$respond = $this->newsletter_model->delete_newsletter_AJAX($id);
        		echo json_encode($respond);
	        } else {
	            echo json_encode(array(
	            	'status' => 'error',
	            	'message' => 'Nincs engedélye a művelet végrehajtásához!'
	            ));
	        }
        }
	}

	/**
	 * Hírlevél küldése
	 */
	public function send_newsletter()
	{
		header('Content-Type: text/event-stream');
		// recommended to prevent caching of event data.
		header('Cache-Control: no-cache');
			
		set_time_limit(0); 
		//ob_implicit_flush(true);

		$newsletter_id = $this->request->get_query('newsletter_id');
		$this->newsletter_model->send_newsletter($newsletter_id);
	}
	
	/**
	 * Elküldött hírlevelek
	 */
	public function newsletter_stats()
	{
		$view = new View();
		
		$view->title = 'Elküldött hírlevelek oldal';
		$view->description = 'Elküldött hírlevél oldal description';

		$view->add_links(array('datatable', 'bootbox', 'vframework', 'newsletter_stats'));
		
		$view->newsletters = $this->newsletter_model->newsletter_stats_query();	
//$this->view->debug(true);	
		$view->set_layout('tpl_layout');
		$view->render('newsletter/tpl_newsletter_stats');	
	}	
	
}	
?>