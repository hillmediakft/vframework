<?php
class Testimonials extends Admin_controller {

	function __construct()
	{
		parent::__construct();
        Auth::handleLogin();
		$this->loadModel('testimonials_model');
	}

	public function index()
	{
/*		Auth::handleLogin();

		if (!Acl::create()->userHasAccess('home_menu')) {
		exit('nincs hozzáférése');
		}

*/
		// adatok bevitele a view objektumba
		$this->view->title = 'testimonials oldal';
		$this->view->description = 'testimonials oldal description';
		
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/bootbox/bootbox.min.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/testimonials.js');
		
		$this->view->all_testimonials = $this->testimonials_model->all_testimonials();	
		
		$this->view->render('testimonials/tpl_testimonials');
	}
	
	
	/**
	 * A testimonialsek sorrendjének módosításakor meghívott action (testimonials/order)
	 *
	 * Megvizsgálja, hogy a kérés xmlhttprequest volt-e (Ajax), ha igen meghívja a testimonials_order() metódust 
	 *
	 * @return void
	 */
	public function new_testimonial()
	{
		if($this->request->has_post('submit_new_testimonial')) {
			$this->testimonials_model->new_testimonial();
			Util::redirect('testimonials');
		}
			
		// adatok bevitele a view objektumba
		$this->view->title = 'Új testimonials oldal';
		$this->view->description = 'Új testimonials oldal description';
		
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/bootbox/bootbox.min.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/new_testimonial.js');
		
	
//		$this->view->testimonials = $this->testimonials_model->get_testimonials_data();	
		
		$this->view->render('testimonials/tpl_new_testimonial');	
		
	}
	
	/**
	 *	Rólunk mondták elemek módosítása
	 *
	 */
	public function edit()
	{
		$id = $this->request->get_params('id');

		if($this->request_has('submit_update_testimonial')) {
			$result = $this->testimonials_model->update_testimonial($id);
			Util::redirect('testimonials');
		}
		
		// adatok bevitele a view objektumba
		$this->view->title = 'Rólunk mondták szerkesztése';
		$this->view->description = 'Rólunk mondták szerkesztése description';
		
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/bootbox/bootbox.min.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/edit_testimonial.js');
		
		// visszadja a szerkesztendő oldal adatait egy tömbben (page_id, page_title ... stb.)
		$this->view->data_arr = $this->testimonials_model->testimonial_data_query($id);
		
		$this->view->render('testimonials/tpl_edit_testimonial');
	
	}
	
	/**
	 *	Rólunk mondták elem törlése
	 *
	 */
	public function delete()
	{
		$id = $this->request->get_params('id');
		$result = $this->testimonials_model->delete_testimonial($id);
		Util::redirect('testimonials');
	}
	
}
?>