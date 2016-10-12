<?php
namespace System\Admin\Model;

use System\Core\Admin_model;

class BlogCategory_model extends Admin_model {

	protected $table = 'blog_category';	
	protected $id = 'category_id';	

	function __construct()
	{
		parent::__construct();
	}

	/**
	 *	Visszaadja a blog_category tábla tartalmát
	 *	Ha kap egy id paramétert (integer), akkor csak egy sort ad vissza a táblából
	 *
	 *	@param $id Integer 
	 */
	public function selectCategory($id = null)
	{
		if(!is_null($id)){
			$id = (int)$id;
			$this->query->set_where('category_id', '=', $id); 
		}
		return $this->query->select(); 
	}

	/**
	 * Kategória törlése a blog_category táblából
	 */
 	public function deleteCategory($id)
	{
		return $this->query->delete('category_id', '=', $id);
	}

	/**
	 * Kategória név módosítás
	 */
 	public function updateCategory($id, $name)
 	{
		$this->query->set_where('category_id', '=', $id);
		return $this->query->update(array('category_name' => $name));
 	}

 	/**
 	 * Kategória hozzáadása
 	 */
 	public function insertCategory($new_name)
 	{
		return $this->query->insert(array('category_name' => $new_name)); 		
 	}	

}
?>