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

}
?>