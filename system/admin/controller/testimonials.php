<?php
class Testimonials extends Admin_controller {

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		// adatok bevitele a view objektumba
		$this->view->title = 'testimonials oldal';
		$this->view->description = 'testimonials oldal description';
		
		$this->view->add_links(array('bootbox', 'testimonials'));
		
		$this->view->all_testimonials = $this->testimonials_model->all_testimonials();	
		
		$this->view->render('testimonials/tpl_testimonials');
	}
	
	
	/**
	 * A testimonialsek sorrendjének módosításakor meghívott action (testimonials/order)
	 *
	 * Megvizsgálja, hogy a kérés xmlhttprequest volt-e (Ajax), ha igen meghívja a testimonials_order() metódust 
	 */
	public function insert()
	{
		if($this->request->has_post()) {
			$this->testimonials_model->insert_testimonial();
			Util::redirect('testimonials');
		}
			
		$this->view->title = 'Új testimonials oldal';
		$this->view->description = 'Új testimonials oldal description';
		
		$this->view->add_links(array('bootbox', 'testimonial_insert'));
	
//		$this->view->testimonials = $this->testimonials_model->get_testimonials_data();	
		
		$this->view->render('testimonials/tpl_testimonial_insert');	
	}
	
	/**
	 *	Rólunk mondták elemek módosítása
	 */
	public function update()
	{
		$id = (int)$this->request->get_params('id');

		if($this->request->has_post()) {
			$this->testimonials_model->update_testimonial($id);
			Util::redirect('testimonials');
		}
		
		// adatok bevitele a view objektumba
		$this->view->title = 'Rólunk mondták szerkesztése';
		$this->view->description = 'Rólunk mondták szerkesztése description';
		
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/bootbox/bootbox.min.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/testimonial_update.js');
		
		// visszadja a szerkesztendő oldal adatait egy tömbben (page_id, page_title ... stb.)
		$this->view->data_arr = $this->testimonials_model->testimonial_data_query($id);
		
		$this->view->render('testimonials/tpl_testimonial_update');
	
	}
	
	/**
	 *	Rólunk mondták elem törlése
	 */
	public function delete()
	{
		$id = (int)$this->request->get_params('id');
		$result = $this->testimonials_model->delete_testimonial($id);
		Util::redirect('testimonials');
	}
	
}
?>