<?php 
namespace System\Admin\Model;
use System\Core\AdminModel;

class Pages_model extends AdminModel {

	protected $table = 'pages';

	/**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
	function __construct()
	{
		parent::__construct();
	}
	
	public function allPages()
	{
		return $this->query->select(); 
	}

	/**
	 *	Egy oldal adatait kérdezi le az adatbázisból (pages tábla)
	 *
	 *	@param	integer $id
	 *	@return	array
	 */
	public function onePage($id)
	{
		$this->query->set_where('id', '=', $id);
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