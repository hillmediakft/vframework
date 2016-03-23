<?php
class Photo_gallery extends Admin_controller {

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->view = new View();

		$this->view->title = 'Fotó galériák oldal';
		$this->view->description = 'Fotó galériák oldal description';
		
		$this->view->add_link('css', ADMIN_CSS . 'pages/portfolio.css');
		$this->view->add_links(array('bootbox', 'mixitup', 'vframework', 'photo_gallery'));
		
		$this->view->categorys = $this->photo_gallery_model->category_query();
		$this->view->all_photos = $this->photo_gallery_model->photo_data_query();
// $this->view->debug(true);
		$this->view->set_layout('tpl_layout');
		$this->view->render('photo_gallery/tpl_photo_gallery');
	}
	
	/**
	 * Új fotó hozzáadása
	 *
	 * @return void
	 */
	public function insert()
	{
		if($this->request->has_post()) {
			$this->photo_gallery_model->insert_photo();
			Util::redirect('photo_gallery');
		}
		
		$this->view = new View();

		$this->view->title = 'Új fotó oldal';
		$this->view->description = 'Új fotó oldal description';
		
		$this->view->add_links(array('bootstrap-fileupload', 'vframework', 'photo_gallery_insert_update'));

		$this->view->categorys = $this->photo_gallery_model->category_query();
// $this->view->debug(true);			
		$this->view->set_layout('tpl_layout');	
		$this->view->render('photo_gallery/tpl_photo_insert');	
	}
	
	/**
	 * Kép adatainak szerkesztése (új kép feltöltése, szöveg módosítása, kiemelés, kategória módosítása)
	 *
	 * @return void
	 */
	public function update()
	{
		$id = (int)$this->request->get_params('id');

		if($this->request->has_post()) {
			$result = $this->photo_gallery_model->update_photo($id);
			Util::redirect('photo-gallery');
		}
		
		$this->view = new View();

		$this->view->title = 'Fotó szerkesztése oldal';
		$this->view->description = 'Fotó szerkesztése description';

		$this->view->add_links(array('bootstrap-fileupload', 'vframework', 'photo_gallery_insert_update'));
		
		$this->view->categorys = $this->photo_gallery_model->category_query();
		$this->view->photo = $this->photo_gallery_model->photo_data_query($id);	
// $this->view->debug(true);		
		$this->view->set_layout('tpl_layout');	
		$this->view->render('photo_gallery/tpl_photo_update');	
	}
	
	/**
	 *	Photo törlése AJAX-al
	 */
	public function delete_photo_AJAX()
	{
        if($this->request->is_ajax()){
	        if(1){
	        	// a POST-ban kapott user_id egy string ami egy szám vagy számok felsorolása pl.: "23" vagy "12,45,76" 
	        	$id = $this->request->get_post('item_id');
            	$respond = $this->photo_gallery_model->delete_photo_AJAX($id);
        		echo json_encode($respond);
	        } else {
	            echo json_encode(array(
	            	'status' => 'error',
	            	'message' => 'Nincs engedélye a művelet végrehajtásához!'
	            ));
	        }
        }
	}

/********---------------------------------------*********/

	/**
	 * Kategóriák listája
	 */
	public function category()
	{
		$this->view = new View();

		$this->view->title = 'Admin fotó kategóriák oldal';
		$this->view->description = 'Admin fotó kategóriák oldal description';	

		$this->view->add_links(array('bootbox', 'datatable', 'bootstrap-editable', 'vframework', 'photo_category'));

		$this->view->all_category = $this->photo_gallery_model->category_query();
		$this->view->category_counter = $this->photo_gallery_model->category_counter_query();
//$this->view->debug(true);			
		$this->view->set_layout('tpl_layout');	
		$this->view->render('photo_gallery/tpl_photo_category');	

	}

	/**
	 *	Kategória törlése AJAX-al
	 */
	public function delete_category_AJAX()
	{
        if($this->request->is_ajax()){
	        if(1){
	        	// a POST-ban kapott user_id egy string ami egy szám vagy számok felsorolása pl.: "23" vagy "12,45,76" 
	        	$id = $this->request->get_post('item_id', 'integer');
            	$respond = $this->photo_gallery_model->category_delete($id);
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
	 * Kategória hozzáadása és módosítása (AJAX)
	 */
	public function category_insert_update()
	{
		if ($this->request->is_ajax()) {
			$id = $this->request->get_post('id', 'integer');
			$category_name = $this->request->get_post('data');
			$result = $this->photo_gallery_model->category_insert_update($id, $category_name);
			echo json_encode($result);
		}
	}

}
?>