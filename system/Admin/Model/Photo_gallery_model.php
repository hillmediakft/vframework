<?php 
namespace System\Admin\Model;
use System\Core\AdminModel;

class Photo_gallery_model extends AdminModel {

	protected $table = 'photo_gallery';
	//protected $id = 'id';

	/**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
	function __construct()
	{
		parent::__construct();
	}
	

	/**
	 *	Visszaadja a photo_gallery tábla egy kategóriájának elemeit
	 *	Ha kap egy id paramétert (integer), akkor csak egy sort ad vissza a táblából
	 *
	 *	@param $id Integer 
	 *	@param $langcode string 
	 */
	public function findPhoto($id = null, $langcode = null)
	{
		$this->query->set_columns(
			"photo_gallery.*,
			 photo_gallery_translation.caption,
			 photo_gallery_translation.language_code,
			 photo_category_translation.category_name"
			);

		$this->query->set_join('inner', 'photo_gallery_translation', 'photo_gallery.id', '=', 'photo_gallery_translation.photo_id'); 
		$this->query->set_join('left', 'photo_category_translation', '(photo_gallery.category_id = photo_category_translation.category_id AND photo_category_translation.language_code = photo_gallery_translation.language_code)'); 
		$this->query->set_orderby('photo_gallery.id');
		
		if (!is_null($langcode)) {
			$this->query->set_where('photo_gallery_translation.language_code', '=', $langcode);
		}
		
		if(!is_null($id)){
			$this->query->set_where('photo_gallery.id', '=', $id); 
		}
		
//$this->query->debug();
		return $this->query->select();
	}

	/**
	 * Egy rekord filename mezőjét adja vissza (kép neve)
	 */
	public function findFilename($id)
	{
		$this->query->set_columns(array('filename'));
		$this->query->set_where('id', '=', $id);
		$result = $this->query->select();
		return $result[0]['filename'];
	}

	/**
	 * INSERT
	 */
	public function insert($data)
	{
		return $this->query->insert($data);		
	}

	/**
	 * UPDATE
	 */
	public function update($id, $data)
	{
		$this->query->set_where('id', '=', $id);
		return $this->query->update($data);		
	}

	/**
	 * DELETE
	 */
	public function delete($id)
	{
		return $this->query->delete('id', '=', $id);
	}

	/**
	 * Egy kategóriához tartozó rekordok törlése
	 */
	public function deleteWhereCategory($id)
	{
		return $this->query->delete('category_id', '=', $id);
	}

	/**
	 * Egy kategóriához tartozó képek nevének lekérdezése 
	 */
	public function selectFilenameWhereCategory($id)
	{
		$this->query->set_columns(array('filename'));
		$this->query->set_where('category_id', '=', $id);
		return $this->query->select();			
	}

	/**
	 * Visszaadja a photo_gallery táblából hogy egy kép kategóriához hány elem tartozik
	 * A tömb kulcs a kategória id, az érték a kategóriához tartozó képek száma 
	 *
	 * @return array
	 */
	public function categoryCounter()
	{
		$this->query->set_columns('category_id, COUNT(category_id) AS item_numbers');
		$this->query->set_groupby('category_id');
		$result = $this->query->select();

		$temp = array();
		foreach ($result as $key => $value) {
			$temp[$value['category_id']] = $value['item_numbers']; 
		}

		return $temp;
	}

}
?>