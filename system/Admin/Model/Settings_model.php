<?php
namespace System\Admin\Model;
use System\Core\AdminModel;

class Settings_model extends AdminModel {

	protected $table = 'settings';

	/**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Oldal szintű beállítások lekérdezése a settings táblából
	 *
	 * @return array a beállítások tömbje
	 */
	public function get_settings()
	{
		$result = $this->query->select();
        return $result[0];
	}
	
	/**
	 * UPDATE
	 */
	public function update($id, $data)
	{
		$this->query->set_where('id', '=', $id);
		return $this->query->update($data);		
	}

}
?>