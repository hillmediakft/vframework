<?php
namespace System\Admin\Model;

use System\Core\AdminModel;

class Blog_model extends AdminModel {

	protected $table = 'blog';	

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
		$this->query->set_columns(array('blog.id','blog.title','blog.body','blog.picture','blog.add_date', 'blog_category.category_name')); 
		$this->query->set_join('left', 'blog_category', 'blog.category_id', '=', 'blog_category.id'); 
		
		if(!is_null($id)){
			$id = (int)$id;
			$this->query->set_where('blog.id', '=', $id); 
			$result = $this->query->select(); 
			return $result[0];

		} else {
			return $this->query->select(); 
		}
	}

	/**
	 *	Visszaadja a blog táblából a blog_category oszlopot
	 */
	public function categoryCounter()
	{
		$this->query->set_columns('category_id');
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
		$this->query->set_where('id','=', $id);
		return $this->query->update($data);
	}
	
	/**
	 * Rekord törlése id alapján
	 */
	public function delete($value)
	{
		return $this->query->delete('id', '=', $value);
	}

 	/**
 	 * Blog törlése blog_category oszlop alapján
 	 */
	public function deleteWhereCategory($id)
 	{
		return $this->query->delete('category_id', '=', $id);
 	}

	/**
	 * Egy bloghoz tartozó kép nevét adja vissza
	 */
	public function selectPicture($id)
	{
		$this->query->set_columns(array('picture'));
		$this->query->set_where('id', '=', $id);
		$result = $this->query->select();
		return $result[0]['picture'];
	}

	/**
	 * Visszaadja a blog képek nevét, ahol a blog_category = a $category paraméterrel
	 */
	public function selectPictureWhereCategory($category)
	{
		$this->query->set_columns(array('picture'));
		$this->query->set_where('category_id', '=', $category);
		return $this->query->select();
	}

}
?>