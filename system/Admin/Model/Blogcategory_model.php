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
	 *	@param integer 	$id  
	 *	@param string 	$lang  - csak a megadott nyelvű elemet adja vissza
	 */
	public function findCategory($id = null, $lang = null)
	{
		$this->query->set_columns(
			'blog_category_translation.category_id AS id,
			blog_category_translation.category_name,
			languages.code AS language_code'	
			);

		$this->query->set_join('inner', 'blog_category_translation', 'blog_category.id', '=', 'blog_category_translation.category_id'); 
		$this->query->set_join('inner', 'languages', 'blog_category_translation.language_code', '=', 'languages.code'); 

		if (!is_null($lang)) {
			$this->query->set_where('blog_category_translation.language_code', '=', $lang); 
		}
		
		if(!is_null($id)){
			$this->query->set_where('blog_category.id', '=', $id); 
		}
		
		return $this->query->select(); 
		
	}

	/**
	 * Kategória törlése
	 */
 	public function deleteCategory($id)
	{
		return $this->query->delete('id', '=', $id);
	}

 	/**
 	 * Kategória hozzáadása
 	 */
 	public function insertCategory()
 	{
		return $this->query->insert(array('id' => null));
 	}

		/**
		 * Kategória id módosítás
		 */
	 	public function updateCategory($id)
	 	{
			$this->query->set_where('id', '=', $id);
			return $this->query->update(array('id' => $id));
	 	}
}
?>