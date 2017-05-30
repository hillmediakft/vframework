<?php
namespace System\Admin\Model;

use System\Core\AdminModel;

class PhotoCategory_translation_model extends AdminModel {

	protected $table = 'photo_category_translation';	

	function __construct()
	{
		parent::__construct();
	}

 	/**
 	 * INSERT
 	 *
 	 * @param string $category_id
 	 * @param string $language_code
 	 * @param string $category_name
 	 */
 	public function insert($category_id, $language_code, $category_name)
 	{
		return $this->query->insert(array(
			'category_id' => $category_id,
			'language_code' => $language_code,
			'category_name' => $category_name
			));	
 	}

	/**
	 * UPDATE
	 *
	 * @param integer $category_id
	 * @param string $language_code
	 * @param string $category_name
	 */
 	public function update($category_id, $language_code, $category_name)
 	{
		$this->query->set_where('category_id', '=', $category_id);
		$this->query->set_where('language_code', '=', $language_code);
		return $this->query->update(array('category_name' => $category_name));
 	}

		/**
		 * DELETE (ezt a metódust nem kell használni, ha a táblakapcsolatoknál be van állítva az automatikus törlés!)
		 */
	 	public function delete($category_id)
		{
			return $this->query->delete('category_id', '=', $category_id);
		}

}
?>