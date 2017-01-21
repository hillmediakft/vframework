<?php
namespace System\Admin\Model;
use System\Core\AdminModel;

class Content_model extends AdminModel {

	protected $table = 'content';

	/**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
	function __construct()
	{
		parent::__construct();
	}
	
	public function allContents()
	{
		$this->query->set_columns(array('id', 'name', 'title')); 
		return $this->query->select(); 
	}
	
	/**
	 * UPDATE content
	 */
	public function update($id, $data)
	{
		$this->query->set_where('id', '=', $id);
		return $this->query->update($data);
	}

	/**
	 *	Egy oldal adatait kérdezi le az adatbázisból (pages tábla)
	 *
	 *	@param	$id String or Integer
	 *	@return	az adatok tömbben
	 */
	public function selectContent($id)
	{
		$this->query->set_where('id', '=', $id);
		$result = $this->query->select();
		return $result[0];
	}
	
}
?>