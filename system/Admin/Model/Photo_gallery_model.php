<?php 
namespace System\Admin\Model;
use System\Core\AdminModel;

class Photo_gallery_model extends AdminModel {

	protected $table = 'photo_gallery';
	protected $id = 'photo_id';

	/**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *	Egy kép adatait kérdezi le az adatbázisból ha van id paraméter (photo_gallery tábla)
	 *
	 *	@param	$id a kép rekordjának azonosítója
	 *	@return	array
	 */
	public function selectOne($id)
	{
		$this->query->set_where('photo_id', '=', $id);
		$result = $this->query->select();
		return $result[0];
	}

	/**
	 * Lekérdez minden rekordot
	 */
	public function selectAll()
	{
		return $this->query->select();
	}	

	/**
	 * Egy rekord filename mezőjét adja vissza (kép neve)
	 */
	public function selectFilename($id)
	{
		$this->query->set_columns(array('photo_filename'));
		$this->query->set_where('photo_id', '=', $id);
		$result = $this->query->select();
		return $result[0]['photo_filename'];
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
		$this->query->set_where('photo_id', '=', $id);
		return $this->query->update($data);		
	}

	/**
	 * DELETE
	 */
	public function delete($id)
	{
		return $this->query->delete('photo_id', '=', $id);
	}

	/**
	 * Egy kategóriához tartozó rekordok törlése
	 */
	public function deleteWhereCategory($id)
	{
		return $this->query->delete('photo_category', '=', $id);
	}

	/**
	 * Egy kategóriához tartozó képek nevének lekérdezése 
	 */
	public function selectFilenameWhereCategory($id)
	{
		$this->query->set_columns(array('photo_filename'));
		$this->query->set_where('photo_category', '=', $id);
		return $this->query->select();			
	}

	/**
	 *	Visszaadja a photo_gallery táblából a photo_category oszlopot
	 */
	public function categoryCounter()
	{
		$this->query->set_columns('photo_category'); 
		return $this->query->select(); 
	}

}
?>