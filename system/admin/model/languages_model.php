<?php 
namespace System\Admin\Model;
use System\Core\Admin_model;

class Languages_model extends Admin_model {

	protected $table = 'languages';
	protected $id = 'text_id';

	/**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
	function __construct()
	{
		parent::__construct();
	}
	
	public function get_language_data()
	{
		return $this->query->select(); 
	}
	

	public function updateLang($id, $column, $text)
	{
		$this->query->set_where('text_id', '=', $id);
		return $this->query->update(array($column => $text));
	}

}
?>