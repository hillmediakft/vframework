<?php 
class Newsletter extends Admin_controller {

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->view = new View();

		$this->view->title = 'Hírlevél oldal';
		$this->view->description = 'Hírlevél oldal description';

		$this->view->add_links(array('datatable', 'bootbox', 'vframework', 'newsletter_eventsource'));

		$this->view->newsletters = $this->newsletter_model->newsletter_query();	
//$this->view->debug(true);	
		$this->view->set_layout('tpl_layout');
		$this->view->render('newsletter/tpl_newsletter');	
	}
	
	/**
	 * Hírlevél hozzáadása
	 */
	public function insert()
	{
		if($this->request->has_post()) {
			$this->newsletter_model->insert_newsletter();
			Util::redirect('newsletter');
		}

		$this->view = new View();

		$this->view->title = 'Hírlevél hozzáadása';
		$this->view->description = 'Hírlevél oldal description';
		
		$this->view->add_links(array('bootbox', 'ckeditor', 'vframework', 'newsletter_insert'));
		
		$this->view->set_layout('tpl_layout');
		$this->view->render('newsletter/tpl_newsletter_insert');	
	}
	
	/**
	 * Hírlevél módosítása
	 */
	public function update()
	{
		if($this->request->has_post()) {
			$this->newsletter_model->update_newsletter($this->request->get_params('id'));
			Util::redirect('newsletter');
		}

		$this->view = new View();

		$this->view->title = 'Hírlevél szerkesztése';
		$this->view->description = 'Hírlevél oldal description';
		
		$this->view->add_links(array('bootbox', 'ckeditor', 'vframework', 'newsletter_update'));
		
		$this->view->newsletter = $this->newsletter_model->newsletter_query($this->request->get_params('id'));
	
		$this->view->set_layout('tpl_layout');
		$this->view->render('newsletter/tpl_newsletter_update');	
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
		$this->view = new View();
		
		$this->view->title = 'Elküldött hírlevelek oldal';
		$this->view->description = 'Elküldött hírlevél oldal description';

		$this->view->add_links(array('datatable', 'bootbox', 'vframework', 'newsletter_stats'));
		
		$this->view->newsletters = $this->newsletter_model->newsletter_stats_query();	
//$this->view->debug(true);	
		$this->view->set_layout('tpl_layout');
		$this->view->render('newsletter/tpl_newsletter_stats');	
	}	
	
}	
?>