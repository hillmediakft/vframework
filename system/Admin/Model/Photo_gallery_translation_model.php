<?php
namespace System\Admin\Model;

use System\Core\AdminModel;

class Photo_gallery_translation_model extends AdminModel {

	protected $table = 'photo_gallery_translation';	

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
	public function update($photo_id, $langcode, $data)
	{
		$this->query->set_where('photo_id','=', $photo_id);
		$this->query->set_where('language_code','=', $langcode);
		return $this->query->update($data);
	}
	

		/**
		 * Rekord törlése photo_id alapján
		 * (A "kapcsolt" rekord automatikusan törlődik, ha törlünk valamit a photo_gallery táblából)
		 */
		/*
		public function delete($photo_id)
		{
			return $this->query->delete('photo_id', '=', $photo_id);
		}
		*/

    /**
     * Megadott nyelvi kódú elem létezését vizsgálja
     *
     * @param integer $photo_id
     * @param string $langcode
     * @return bool
     */
    public function checkLangVersion($photo_id, $langcode)
    {
    	$this->query->set_columns('COUNT(id) AS counter');
    	$this->query->set_where('photo_id', '=', $photo_id);
    	$this->query->set_where('language_code', '=', $langcode);
    	$result = $this->query->select();
    	return ($result[0]['counter'] == 1) ? true : false;
    }

}
?>