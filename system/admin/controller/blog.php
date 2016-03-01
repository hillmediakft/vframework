<?php
class Blog extends Admin_controller {

	function __construct()
	{
		parent::__construct();
	}
    
	public function index()
	{
		// adatok bevitele a view objektumba
		$this->view->title = 'Admin blog oldal';
		$this->view->description = 'Admin blog oldal description';	
		// linkek
		$this->view->add_links(array('select2', 'datatable', 'bootbox', 'vframework', 'blog'));

		$this->view->all_blog = $this->blog_model->blog_query2();

// $this->view->debug(true);		
		
		$this->view->render('blog/tpl_blog');
	}
    
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

		$this->view->title = 'Admin blog oldal';
		$this->view->description = 'Admin blog oldal description';	
		// linkek
		$this->view->add_links(array('datepicker', 'bootstrap-fileupload', 'ckeditor', 'vframework', 'blog_insert'));

		$this->view->category_list = $this->blog_model->blog_category_query();
		
		$this->view->render('blog/tpl_blog_insert');
	
	}
    
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

		$this->view->title = 'Admin blog oldal';
		$this->view->description = 'Admin blog oldal description';	
        // linkek	
		$this->view->add_links(array('datepicker', 'bootstrap-fileupload', 'ckeditor', 'vframework', 'blog_update'));
        
		$this->view->category_list = $this->blog_model->blog_category_query();
		$this->view->content = $this->blog_model->blog_query2($this->request->get_params('id'));
		
//$this->view->debug(true);		

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


	public function category()
	{
		$this->view->title = 'Admin blog oldal';
		$this->view->description = 'Admin blog oldal description';	
        // linkek	
		$this->view->add_links(array('select2', 'datatable', 'bootbox', 'vframework', 'blog_category'));

		$this->view->all_blog_category = $this->blog_model->blog_category_query();
		$this->view->category_counter = $this->blog_model->blog_category_counter_query();

//$this->view->debug(true);			
		
		$this->view->render('blog/tpl_blog_category');	

	}
	
	public function category_insert()
	{
		if($this->request->has_post('submit_category_insert')){
			$result = $this->blog_model->category_insert();
			if($result){
				Util::redirect('blog/category');
			} else {
				Util::redirect('blog/category_insert');
			}
		}

		$this->view->title = 'Admin blog oldal';
		$this->view->description = 'Admin blog oldal description';	
		// linkek
		$this->view->add_links(array('vframework', 'blog_category_insert'));   
		
		$this->view->render('blog/tpl_category_insert');	
	}

	
	public function category_update()
	{
		if($this->request->has_post('submit_category_update')){
			$result = $this->blog_model->category_update($this->request->get_params('id'));
			if($result){
				Util::redirect('blog/category');
			} else {
				Util::redirect('blog/category_update/'. $this->request->get_params('id'));
			}
		}

		$this->view->title = 'Admin blog oldal';
		$this->view->description = 'Admin blog oldal description';	
		// linkek
		$this->view->add_links(array('vframework', 'blog_category_update'));   
		
		$this->view->content = $this->blog_model->blog_category_query($this->request->get_params('id'));
		
		$this->view->render('blog/tpl_category_update');	
	}

}
?>