<?php
namespace System\Admin\Controller;

use System\Core\Admin_controller;
use System\Core\View;
use System\Libs\Session;
use System\Libs\Message;
use System\Libs\Validate;

class Testimonials extends Admin_controller {

	function __construct()
	{
		parent::__construct();
		$this->loadModel('testimonials_model');
	}

	public function index()
	{
		$view = new View();
		
		$data['title'] = 'testimonials oldal';
		$data['description'] = 'testimonials oldal description';
		$data['all_testimonials'] = $this->testimonials_model->selectAll();	
		
		$view->add_links(array('bootbox', 'testimonials'));
		$view->render('testimonials/tpl_testimonials', $data);
	}
	
	
	/**
	 * A testimonialsek sorrendjének módosításakor meghívott action (testimonials/order)
	 *
	 * Megvizsgálja, hogy a kérés xmlhttprequest volt-e (Ajax), ha igen meghívja a testimonials_order() metódust 
	 */
	public function insert()
	{
			if($this->request->has_post()) {

				$data['name'] = $this->request->get_post('testimonial_name');
				$data['title'] = $this->request->get_post('testimonial_title');
				$data['text'] = $this->request->get_post('testimonial_text');

				// input adatok tárolása session-ben
				Session::set('testimonial_input', $data);

				// validátor objektum létrehozása
		        $validate = new Validate();

		        // szabályok megadása az egyes mezőkhöz (mező neve, label, szabály)
		        $validate->add_rule('name', 'név', array(
		            'required' => true,
		            'min' => 2
		        ));
		        $validate->add_rule('title', 'beosztás', array(
		            'required' => true
		        ));
		        $validate->add_rule('text', 'vélemény', array(
		            'required' => true
		        ));

		        // üzenetek megadása az egyes szabályokhoz (szabály_neve, üzenet)
		        $validate->set_message('required', 'A :label mező nem lehet üres!');
		        $validate->set_message('min', 'A :label mező túl kevés karaktert tartalmaz!');

		        // mezők validálása
		        $validate->check($data);

		        // HIBAELLENŐRZÉS - ha valamilyen hiba van a form adataiban
		        if(!$validate->passed()){
		            
		            foreach ($validate->get_error() as $value) {
		                Message::set('error', $value);
		            }

					$this->response->redirect('admin/testimonials/insert');

		        } else {
		        	// adatbázisba írás
					$result = $this->testimonials_model->insert($data);

					if($result !== false) {
			            Message::set('success', 'new_testimonial_success');
						Session::delete('testimonial_input');
						$this->response->redirect('admin/testimonials');
					} else {
			            Message::set('error', 'unknown_error');
						$this->response->redirect('admin/testimonials/insert');
					}
		        }
			}
		
		$view = new View();
			
		$data['title'] = 'Új testimonials oldal';
		$data['description'] = 'Új testimonials oldal description';
		
		$view->add_links(array('testimonial_insert','vframework'));
		$view->render('testimonials/tpl_testimonial_insert', $data);
		Session::delete('testimonial_input');
	}
	
	/**
	 *	Rólunk mondták elemek módosítása
	 */
	public function update($id)
	{
		$id = (int)$id;

			if($this->request->has_post()) {

				$data['name'] = $this->request->get_post('testimonial_name');
				$data['title'] = $this->request->get_post('testimonial_title');
				$data['text'] = $this->request->get_post('testimonial_text');

				$result = $this->testimonials_model->update($id, $data);

				if($result !== false) {
		            Message::set('success', 'testimonial_update_success');
					$this->response->redirect('admin/testimonials');
				} else {
		            Message::set('error', 'unknown_error');
					$this->response->redirect('admin/testimonials/update');
				}
			}
		
		$view = new View();
		
		$data['title'] = 'Rólunk mondták szerkesztése';
		$data['description'] = 'Rólunk mondták szerkesztése description';
		// visszadja a szerkesztendő oldal adatait egy tömbben (page_id, page_title ... stb.)
		$data['data_arr'] = $this->testimonials_model->selectOne($id);
		
		$view->add_links(array('bootbox', 'vframework', 'testimonial_update'));
		$view->render('testimonials/tpl_testimonial_update', $data);
	}
	
	/**
	 *	Rólunk mondták elem törlése
	 */
	public function delete($id)
	{
		$result = $this->testimonials_model->delete( (int)$id );
		
		// ha sikeres a törlés 1 a vissaztérési érték
		if($result === 1) {
            Message::set('success', 'testimonial_delete_success');
		} else {
            Message::set('error', 'unknown_error');
		}

		$this->response->redirect('admin/testimonials');
	}
	
}
?>