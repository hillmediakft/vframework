<?php 
namespace System\Admin\Model;
use System\Core\Admin_model;

class Pages_model extends Admin_model {

	protected $table = 'pages';
	protected $id = 'page_id';

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
		$this->query->set_where('page_id', '=', $id);
		return $this->query->select();
	}

	/**
	 * UPDATE
	 */
	public function update($id, $data)
	{
		$this->query->set_where('page_id', '=', $id);
		return $this->query->update($data);		
	}
		
}
?>