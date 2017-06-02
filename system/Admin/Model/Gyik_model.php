<?php
namespace System\Admin\Model;
use System\Core\AdminModel;

class Gyik_model extends AdminModel {

	protected $table = 'gyik';	

	function __construct()
	{
		parent::__construct();
	}
   
	/**
	 *	Visszaadja a gyik tábla egy kategóriájának elemeit
	 *	Ha kap egy id paramétert (integer), akkor csak egy sort ad vissza a táblából
	 *
	 *	@param $id Integer 
	 *	@param $langcode string 
	 */
	public function findGyik($id = null, $langcode = null)
	{
		$this->query->set_table('gyik');
		$this->query->set_columns(
			"gyik.*,
			 gyik_translation.title,
			 gyik_translation.description,
			 gyik_translation.language_code,
			 gyik_category_translation.category_name"
			);

		$this->query->set_join('inner', 'gyik_translation', 'gyik.id', '=', 'gyik_translation.gyik_id'); 
		$this->query->set_join('left', 'gyik_category_translation', '(gyik.category_id = gyik_category_translation.category_id AND gyik_category_translation.language_code = gyik_translation.language_code)'); 
		$this->query->set_orderby('gyik.id');
		
		if (!is_null($langcode)) {
			$this->query->set_where('gyik_translation.language_code', '=', $langcode);
		}
		
		if(!is_null($id)){
			$this->query->set_where('gyik.id', '=', $id); 
		}
		
//$this->query->debug();
		return $this->query->select();


	}

	/**
	 *	Visszaadja a gyik táblából hogy egy gyik kategóriához hány elem tartozik
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
 	 * Gyik törlése gyik_category oszlop alapján
 	 */
	public function deleteWhereCategory($id)
 	{
		return $this->query->delete('category_id', '=', $id);
 	}

    /**
     * 	Status mező értékét módosítja
     * 	
     * 	@param	integer	$id	
     * 	@param	integer	$data (0 vagy 1)	
     * 	@return integer
     */
    public function changeStatus($id, $data)
    {
        $this->query->set_where('id', '=', $id);
        return $this->query->update(array('status' => $data));
    }

}
?>