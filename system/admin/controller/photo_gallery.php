<?php
class Photo_gallery extends Admin_controller {

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		// adatok bevitele a view objektumba
		$this->view->title = 'Fotó galériák oldal';
		$this->view->description = 'Fotó galériák oldal description';
		
		$this->view->add_link('css', ADMIN_CSS . 'pages/portfolio.css');
		$this->view->add_links(array('bootbox', 'photo_gallery', 'mixitup'));
		
		$this->view->all_photos = $this->photo_gallery_model->all_photos();
		
		$this->view->render('photo_gallery/tpl_photo_gallery');
	}
	
	/**
	 * Új fotó hozzáadása
	 *
	 * @return void
	 */
	public function new_photo()
	{
		if($this->request->has_post('submit_new_photo')) {
			$this->photo_gallery_model->save_photo();
			Util::redirect('photo_gallery');
		}
			
		$this->view->title = 'Új fotó oldal';
		$this->view->description = 'Új fotó oldal description';
		
		$this->view->add_links(array('bootstrap-fileupload'));
		$this->view->add_link('js', ADMIN_JS . 'pages/common.js');

		$this->view->render('photo_gallery/tpl_new_photo');	
	}
	
	/**
	 * Kép adatainak szerkesztése (új kép feltöltése, szöveg módosítása, kiemelés, kategória módosítása)
	 *
	 * @return void
	 */
	public function edit()
	{
		$id = (int)$this->request->get_params('id');

		if($this->request->has_post('submit_update_photo')) {
			
			$result = $this->photo_gallery_model->update_photo($id);
			Util::redirect('photo-gallery');
		}
			
		// adatok bevitele a view objektumba
		$this->view->title = 'Fotó szerkesztése oldal';
		$this->view->description = 'Fotó szerkesztése description';

		$this->view->add_links(array('bootstrap-fileupload'));
		$this->view->add_link('js', ADMIN_JS . 'pages/common.js');
		
		$this->view->photo = $this->photo_gallery_model->photo_data_query($id);	
		
		$this->view->render('photo_gallery/tpl_edit_photo');	
		
	}
	
	/**
	 *	Kép törlése a photo_gallery-ből
	 */
	public function delete()
	{
		$id = (int)$this->request->get_params('id');
		$this->photo_gallery_model->delete_photo($id);
		Util::redirect('photo-gallery');
	}
	
}
?>