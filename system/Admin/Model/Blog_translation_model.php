<?php
namespace System\Admin\Model;

use System\Core\AdminModel;

class Blog_translation_model extends AdminModel {

	protected $table = 'blog_translation';	

	function __construct()
	{
		parent::__construct();
	}
   
    /**
     * Rekord INSERT
     */
   	public function insert($data)
	{
		return $this->query->insert($data);
	}

	/**
	 * Rekord UPDATE
	 */
	public function update($blog_id, $langcode, $data)
	{
		$this->query->set_where('blog_id','=', $blog_id);
		$this->query->set_where('language_code','=', $langcode);
		return $this->query->update($data);
	}
	
	/**
	 * Rekord törlése blog_id alapján
	 */
	public function delete($blog_id)
	{
		return $this->query->delete('blog_id', '=', $blog_id);
	}

 	/**
 	 * Blog törlése blog_category oszlop alapján
 	 */
	public function deleteWhereCategory($category_id)
 	{
		return $this->query->delete('category_id', '=', $category_id);
 	}

}
?>