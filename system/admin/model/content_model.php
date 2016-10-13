<?php
namespace System\Admin\Model;
use System\Core\Admin_model;

class Content_model extends Admin_model {

	protected $table = 'content';
	protected $id = 'content_id';

	/**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
	function __construct()
	{
		parent::__construct();
	}
	
	public function allContents()
	{
		$this->query->set_columns(array('content_id', 'content_name', 'content_title')); 
		return $this->query->select(); 
	}
	
	/**
	 * UPDATE content
	 */
	public function update($id, $data)
	{
		$this->query->set_where('content_id', '=', $id);
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
		$this->query->set_where('content_id', '=', $id);
		$result = $this->query->select();
		return $result[0];
	}
	
}
?>