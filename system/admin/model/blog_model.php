<?php
namespace System\Admin\Model;

use System\Core\Admin_model;

class Blog_model extends Admin_model {

	protected $table = 'blog';	
	protected $id = 'blog_id';	

	function __construct()
	{
		parent::__construct();
	}
   
	/**
	 *	Visszaadja a blog tábla egy kategóriájának elemeit
	 *	Ha kap egy id paramétert (integer), akkor csak egy sort ad vissza a táblából
	 *
	 *	@param $id Integer 
	 */
	public function selectBlog($id = null)
	{
		$this->query->set_columns(array('blog.blog_id','blog.blog_title','blog.blog_body','blog.blog_picture','blog.blog_add_date', 'blog_category.category_name')); 
		$this->query->set_join('left', 'blog_category', 'blog.blog_category', '=', 'blog_category.category_id'); 
		if(!is_null($id)){
			$id = (int)$id;
			$this->query->set_where('blog.blog_id', '=', $id); 
		}

		return $this->query->select(); 
	}

	/**
	 *	Visszaadja a blog táblából a blog_category oszlopot
	 */
	public function categoryCounter()
	{
		$this->query->set_columns('blog_category');
		return $this->query->select(); 
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
	public function update($id, $data)
	{
		$this->query->set_where('blog_id','=', $id);
		return $this->query->update($data);
	}
	
	/**
	 * Rekord törlése id alapján
	 */
	public function delete($value)
	{
		return $this->query->delete('blog_id', '=', $value);
	}

 	/**
 	 * Blog törlése blog_category oszlop alapján
 	 */
	public function deleteWhereCategory($id)
 	{
		return $this->query->delete('blog_category', '=', $id);
 	}

	/**
	 * Egy bloghoz tartozó kép nevét adja vissza
	 */
	public function selectPicture($id)
	{
		$this->query->set_columns(array('blog_picture'));
		$this->query->set_where('blog_id', '=', $id);
		$result = $this->query->select();
		return $result[0]['blog_picture'];
	}

	/**
	 * Visszaadja a blog képek nevét, ahol a blog_category = a $category paraméterrel
	 */
	public function selectPictureWhereCategory($category)
	{
		$this->query->set_columns(array('blog_picture'));
		$this->query->set_where('blog_category', '=', $category);
		return $this->query->select();
	}

}
?>