<?php
namespace System\Admin\Model;

use System\Core\AdminModel;

class BlogCategory_model extends AdminModel {

	protected $table = 'blog_category';	

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
			$this->query->set_where('id', '=', $id); 
		}
		return $this->query->select(); 
	}

	/**
	 * Kategória törlése a blog_category táblából
	 */
 	public function deleteCategory($id)
	{
		return $this->query->delete('id', '=', $id);
	}

	/**
	 * Kategória név módosítás
	 */
 	public function updateCategory($id, $new_names)
 	{
		$this->query->set_where('id', '=', $id);
		return $this->query->update($new_names);
 	}

 	/**
 	 * Kategória hozzáadása
 	 */
 	public function insertCategory($new_names)
 	{
		return $this->query->insert($new_names); 		
 	}	

}
?>