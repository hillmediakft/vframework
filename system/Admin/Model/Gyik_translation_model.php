<?php
namespace System\Admin\Model;
use System\Core\AdminModel;

class Gyik_translation_model extends AdminModel {

	protected $table = 'gyik_translation';	

	function __construct()
	{
		parent::__construct();
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
	public function update($gyik_id, $langcode, $data)
	{
		$this->query->set_where('gyik_id','=', $gyik_id);
		$this->query->set_where('language_code','=', $langcode);
		return $this->query->update($data);
	}
	
	/**
	 * Rekord törlése gyik_id alapján
	 */
	public function delete($gyik_id)
	{
		return $this->query->delete('gyik_id', '=', $gyik_id);
	}

 	/**
 	 * Gyik törlése gyik_category oszlop alapján
 	 */
	public function deleteWhereCategory($category_id)
 	{
		return $this->query->delete('category_id', '=', $category_id);
 	}

}
?>