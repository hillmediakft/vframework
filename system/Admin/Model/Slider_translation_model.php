<?php
namespace System\Admin\Model;
use System\Core\AdminModel;

class Slider_translation_model extends AdminModel {

	protected $table = 'slider_translation';	

	function __construct()
	{
		parent::__construct();
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
	public function update($slider_id, $langcode, $data)
	{
		$this->query->set_where('slider_id','=', $slider_id);
		$this->query->set_where('language_code','=', $langcode);
		return $this->query->update($data);
	}
	
		/**
		 * Rekord törlése slider_id alapján
		 * Ezt a metódust nem kell használni,
		 * ha a törlés automatikusra van állítva az adatbázisban egy slider törlésekor a slider táblából
		 */
		public function delete($slider_id)
		{
			return $this->query->delete('slider_id', '=', $slider_id);
		}

}
?>