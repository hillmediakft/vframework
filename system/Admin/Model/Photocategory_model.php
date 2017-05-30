<?php 
namespace System\Admin\Model;
use System\Core\AdminModel;
use System\Libs\Config;

class PhotoCategory_model extends AdminModel {

	protected $table = 'photo_category';
	//protected $id = 'id';

	function __construct()
	{
		parent::__construct();
	}
	

	/**
	 *	Visszaadja a photo_category kategóriákat
	 *	Ha kap egy id paramétert (integer), akkor csak egy sort ad vissza a táblából
	 *
	 *	@param integer 	$id  
	 *	@param string 	$lang  - csak a megadott nyelvű elemet adja vissza
	 */
	public function findCategory($id = null, $lang = null)
	{
		$this->query->set_columns(
			"photo_category_translation.category_id AS id,
			photo_category_translation.category_name,
			photo_category_translation.language_code AS language_code"
			);

		$this->query->set_join('inner', 'photo_category_translation', 'photo_category.id', '=', 'photo_category_translation.category_id'); 

		if (!is_null($lang)) {
			$this->query->set_where('photo_category_translation.language_code', '=', $lang); 
		}
		
		if(!is_null($id)){
			$this->query->set_where('photo_category.id', '=', $id); 
		}
		
		return $this->query->select(); 
	}

 	/**
 	 * Kategória hozzáadása
 	 */
 	public function insertCategory()
 	{
		return $this->query->insert(array('id' => null));
 	}

	/**
	 * Kategória törlése
	 */
	public function deleteCategory($id)
	{
		return $this->query->delete('id', '=', $id);		
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