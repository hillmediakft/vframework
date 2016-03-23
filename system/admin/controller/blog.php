<?php
class Blog extends Admin_controller {

	function __construct()
	{
		parent::__construct();
	}
    
	public function index()
	{
		$this->view = new View();

		$this->view->title = 'Admin blog oldal';
		$this->view->description = 'Admin blog oldal description';	

		$this->view->add_links(array('datatable', 'bootbox', 'vframework', 'blog'));

		$this->view->all_blog = $this->blog_model->blog_query2();
// $this->view->debug(true);		
		$this->view->set_layout('tpl_layout');
		$this->view->render('blog/tpl_blog');
	}
    
    /**
     * Blog bejegyzés hozzáadása
     */
    public function insert()
	{
		if( $this->request->has_post() ){

			$result = $this->blog_model->insert();
			
			if($result){
				Util::redirect('blog');
			} else {
				Util::redirect('blog/insert');
			}
		}

		$this->view = new View();
		
		$this->view->title = 'Admin blog oldal';
		$this->view->description = 'Admin blog oldal description';	

		$this->view->add_links(array('bootstrap-fileupload', 'ckeditor', 'vframework', 'blog_insert'));

		$this->view->category_list = $this->blog_model->category_query();
		
		$this->view->set_layout('tpl_layout');
		$this->view->render('blog/tpl_blog_insert');
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

		$this->view = new View();

		$this->view->title = 'Admin blog oldal';
		$this->view->description = 'Admin blog oldal description';	

		$this->view->add_links(array('bootstrap-fileupload', 'ckeditor', 'vframework', 'blog_update'));
        
		$this->view->category_list = $this->blog_model->category_query();
		$this->view->content = $this->blog_model->blog_query2($this->request->get_params('id'));
//$this->view->debug(true);		
		$this->view->set_layout('tpl_layout');
		$this->view->render('blog/tpl_blog_update');
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
		$this->view = new View();

		$this->view->title = 'Admin blog oldal';
		$this->view->description = 'Admin blog oldal description';	

		$this->view->add_links(array('datatable', 'bootbox', 'vframework', 'blog_category'));

		$this->view->all_blog_category = $this->blog_model->category_query();
		$this->view->category_counter = $this->blog_model->category_counter_query();
//$this->view->debug(true);			
		$this->view->set_layout('tpl_layout');
		$this->view->render('blog/tpl_blog_category');	
	}

	/**
	 * Kategória hozzáadása és módosítása (AJAX)
	 */
	public function category_insert_update()
	{
		if ($this->request->is_ajax()) {
			$id = $this->request->get_post('id', 'integer');
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