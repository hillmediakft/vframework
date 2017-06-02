<?php
namespace System\Admin\Model;
use System\Core\AdminModel;

class GyikCategory_model extends AdminModel {

	protected $table = 'gyik_category';	

	function __construct()
	{
		parent::__construct();
	}

	/**
	 *	Visszaadja a gyik_category tábla tartalmát
	 *	Ha kap egy id paramétert (integer), akkor csak egy sort ad vissza a táblából
	 *
	 *	@param integer 	$id  
	 *	@param string 	$lang  - csak a megadott nyelvű elemet adja vissza
	 */
	public function findCategory($id = null, $lang = null)
	{
		$this->query->set_columns(
			"gyik_category_translation.category_id AS id,
			gyik_category_translation.category_name,
			gyik_category_translation.language_code"
			);

		$this->query->set_join('inner', 'gyik_category_translation', 'gyik_category.id', '=', 'gyik_category_translation.category_id'); 

		if (!is_null($lang)) {
			$this->query->set_where('gyik_category_translation.language_code', '=', $lang); 
		}
		
		if(!is_null($id)){
			$this->query->set_where('gyik_category.id', '=', $id); 
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