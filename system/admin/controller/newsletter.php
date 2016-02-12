<?php 
class Newsletter extends Admin_controller {

	function __construct()
	{
		parent::__construct();
		$this->loadModel('newsletter_model');
	}

	public function index()
	{
	// adatok bevitele a view objektumba
		$this->view->title = 'Hírlevél oldal';
		$this->view->description = 'Hírlevél oldal description';
		
		// az oldalspecifikus css linkeket berakjuk a view objektum css_link tulajdonságába (ami egy tömb)
		// a make_link() metódus az anyakontroller metódusa (így egyszerűen meghívható bármelyik kontrollerben)
		$this->view->css_link[] = $this->make_link('css', ADMIN_ASSETS, 'plugins/select2/select2.css');
		$this->view->css_link[] = $this->make_link('css', ADMIN_ASSETS, 'plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css');
		
		// az oldalspecifikus javascript linkeket berakjuk a view objektum js_link tulajdonságába (ami egy tömb)
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/select2/select2.min.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/datatables/media/js/jquery.dataTables.min.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/bootbox/bootbox.min.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'datatable.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/newsletter_eventsource.js');
		
		$this->view->newsletters = $this->newsletter_model->newsletter_query();	
		
//$this->view->debug(true);	

		// template betöltése
		$this->view->render('newsletter/tpl_newsletter');	

	
	}
	
	
	public function new_newsletter()
	{
		if($this->request->has_post('submit_new_newsletter')) {
			$this->newsletter_model->new_newsletter();
			Util::redirect('newsletter');
		}

		$this->view->title = 'Hírlevél hozzáadása';
		$this->view->description = 'Hírlevél oldal description';
		
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/bootbox/bootbox.min.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/ckeditor/ckeditor.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/new_newsletter.js');
	
		// template betöltése
		$this->view->render('newsletter/tpl_new_newsletter');	
		
	}
	
	
	public function edit_newsletter()
	{
		if($this->request->has_post('submit_edit_newsletter')) {
			$this->newsletter_model->edit_newsletter($this->request->get_params('id'));
			Util::redirect('newsletter');
		}

		$this->view->title = 'Hírlevél szerkesztése';
		$this->view->description = 'Hírlevél oldal description';
		
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/bootbox/bootbox.min.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/ckeditor/ckeditor.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/edit_newsletter.js');
		
		$this->view->newsletter = $this->newsletter_model->newsletter_query($this->request->get_params('id'));
	
		// template betöltése
		$this->view->render('newsletter/tpl_edit_newsletter');	
	
	}


	public function delete_newsletter()
	{
		$this->newsletter_model->delete_newsletter();
		Util::redirect('newsletter');
	}
	

	/**
	 *	Hírlevél törlése AJAX-al
	 *	Az echo $result megy vissza a javascriptnek
	 */
	public function delete_newsletter_AJAX()
	{
		if( $this->request->is_ajax() ) {
			$result = $this->newsletter_model->delete_newsletter_AJAX();
			echo $result;
		}
	}

/*
	public function setid()
	{
		if($this->request->has_post('newsletter_id')) {
			Session::set('newsletter_id', (int)$_POST['newsletter_id']);
			echo json_encode(array('status' => 'done'));
		} else {
			echo json_encode(array('status' => 'fail'));
		}
	}
*/
	
/*--------- EVENTSOURCE --------------------*/	
	
	public function send_newsletter()
	{
		header('Content-Type: text/event-stream');
		// recommended to prevent caching of event data.
		header('Cache-Control: no-cache');
			
		set_time_limit(0); 
		//ob_implicit_flush(true);

//$this->request->get_query('newsletter_id');
$newsletter_id = $this->request->get_query('newsletter_id');


		$this->newsletter_model->send_newsletter($newsletter_id);
	}
/*--------- EVENTSOURCE END--------------------*/	
	

	
	public function newsletter_stats()
	{
		$this->view->title = 'Elküldött hírlevelek oldal';
		$this->view->description = 'Elküldött hírlevél oldal description';
		
		// az oldalspecifikus css linkeket berakjuk a view objektum css_link tulajdonságába (ami egy tömb)
		// a make_link() metódus az anyakontroller metódusa (így egyszerűen meghívható bármelyik kontrollerben)
		$this->view->css_link[] = $this->make_link('css', ADMIN_ASSETS, 'plugins/select2/select2.css');
		$this->view->css_link[] = $this->make_link('css', ADMIN_ASSETS, 'plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css');
		
		// az oldalspecifikus javascript linkeket berakjuk a view objektum js_link tulajdonságába (ami egy tömb)
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/select2/select2.min.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/datatables/media/js/jquery.dataTables.min.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/bootbox/bootbox.min.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'datatable.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/newsletter_stats.js');
		
		$this->view->newsletters = $this->newsletter_model->newsletter_stats_query();	
		
//$this->view->debug(true);	

		// template betöltése
		$this->view->render('newsletter/tpl_newsletter_stats');	
	}	
	
}	
?>