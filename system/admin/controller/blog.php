<?php
namespace System\Admin\Controller;
use System\Core\Admin_controller;
use System\Core\View;
use System\Libs\Util;

class Blog extends Admin_controller {

	function __construct()
	{
		parent::__construct();
		$this->loadModel('blog_model');
	}
    
	public function index()
	{
		$view = new View();

		$view->title = 'Admin blog oldal';
		$view->description = 'Admin blog oldal description';	

		$view->add_links(array('datatable', 'bootbox', 'vframework', 'blog'));

		//$view->setHelper(array('str', 'arr'));

		$view->all_blog = $this->blog_model->blog_query2();
// $view->debug(true);		
		$view->set_layout('tpl_layout');
		$view->render('blog/tpl_blog');
	}
    
    /**
     * Blog bejegyzés hozzáadása
     */
    public function insert()
	{
		if( $this->request->has_post() ){

			// kép feltöltése
			if(isset($_FILES['upload_blog_picture'])) {
				// kép feltöltése, upload_blog_picture() metódussal (visszatér a feltöltött kép elérési útjával, vagy false-al)
				$dest_image = $this->blog_model->upload_blog_picture($_FILES['upload_blog_picture']);
				
				// ha kép feltöltés során hiba lépett fel
				if($dest_image === false){
					$this->response->redirect('admin/blog/insert');
				}
			}
			else {
				throw new Exception('Hiba blog kep feltoltesekor: Nem letezik a \$_FILES[\'upload_blog_picture\'] elem!');
				return false;
			}

			// az adatbázisba kerülő adatok
			$data['blog_title'] = $this->request->get_post('blog_title');
			$data['blog_body'] = $this->request->get_post('blog_body', 'strip_danger_tags');
			$data['blog_picture'] = $dest_image;
			$data['blog_category'] = $this->request->get_post('blog_category');
			$data['blog_add_date'] = date('Y-m-d-G:i');

			// DB lekérdezés
			$result = $this->blog_model->insert($data);

			if($result) {
				Message::set('success', 'Blog hozzáadása sikerült!');
				// Util::redirect('blog');
				$this->response->redirect('admin/blog');
			}
			else {
				Message::set('error' , 'unknown_error');
				// Util::redirect('blog/insert');
				$this->response->redirect('admin/blog/insert');
			}
		}

		$view = new View();
		
		$view->title = 'Admin blog oldal';
		$view->description = 'Admin blog oldal description';	

		$view->add_links(array('bootstrap-fileupload', 'ckeditor', 'vframework', 'blog_insert'));

		$view->category_list = $this->blog_model->category_query();
		
		$view->set_layout('tpl_layout');
		$view->render('blog/tpl_blog_insert');
	}
    
    /**
     * Blog bejegyzés módosítása
     */
	public function update()
	{
		if( $this->request->has_post() ){

			$result = $this->blog_model->update($this->request->get_params('id'));
			if($result){
				Util::redirect('blog');
			} else {
				Util::redirect('blog/update/'. $this->request->get_params('id'));
			}
		}

		$view = new View();

		$view->title = 'Admin blog oldal';
		$view->description = 'Admin blog oldal description';	

		$view->add_links(array('bootstrap-fileupload', 'ckeditor', 'vframework', 'blog_update'));
        
		$view->category_list = $this->blog_model->category_query();
		$view->content = $this->blog_model->blog_query2($this->request->get_params('id'));
//$view->debug(true);		
		$view->set_layout('tpl_layout');
		$view->render('blog/tpl_blog_update');
	}  

	/**
	 *	Blog törlése AJAX-al
	 */
	public function delete_blog_AJAX()
	{
        if($this->request->is_ajax()){
	        if(1){
	        	// a POST-ban kapott user_id egy string ami egy szám vagy számok felsorolása pl.: "23" vagy "12,45,76" 
	        	$id = $this->request->get_post('item_id');
            	$respond = $this->blog_model->delete_blog_AJAX($id);
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
	 * Blog kategóriák 
	 */
	public function category()
	{
		$view = new View();

		$view->title = 'Admin blog oldal';
		$view->description = 'Admin blog oldal description';	

		$view->add_links(array('datatable', 'bootbox', 'vframework', 'blog_category'));

		$view->all_blog_category = $this->blog_model->category_query();
		$view->category_counter = $this->blog_model->category_counter_query();
//$view->debug(true);			
		$view->set_layout('tpl_layout');
		$view->render('blog/tpl_blog_category');	
	}

	/**
	 * Kategória hozzáadása és módosítása (AJAX)
	 */
	public function category_insert_update()
	{
		if ($this->request->is_ajax()) {
			$id = $this->request->get_post('id');
			$category_name = $this->request->get_post('data');
			$result = $this->blog_model->category_insert_update($id, $category_name);
			echo json_encode($result);
		}
	}

	/**
	 *	Kategória törlése (AJAX)
	 */
	public function category_delete()
	{
        if($this->request->is_ajax()){
	        if(1){
	        	$id = $this->request->get_post('item_id', 'integer');
            	$respond = $this->blog_model->category_delete($id);
        		echo json_encode($respond);
	        } else {
	            echo json_encode(array(
	            	'status' => 'error',
	            	'message' => 'Nincs engedélye a művelet végrehajtásához!'
	            ));
	        }
        }
	}	

}
?>