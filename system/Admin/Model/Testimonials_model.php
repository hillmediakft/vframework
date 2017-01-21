<?php
namespace System\Admin\Model;
use System\Core\AdminModel;

class Testimonials_model extends AdminModel {

	protected $table = 'testimonials';

	/**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *	A testimonial táblából kérdez le adatokat
	 *
	 *	@param	$id String or Integer
	 *	@return	az adatok tömbben
	 */
	public function selectOne($id)
	{
		$this->query->set_where('id', '=', $id);
		return $this->query->select();
	}

	public function selectAll()
	{
		return $this->query->select(); 
	}
	
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
	public function insert($data)
	{
		return $this->query->insert($data);
	}
	
	/**
	 *	Vélemény törlése a testimonials táblából
	 *
	 *	@param	$id String or Integer
	 *	@return	az adatok tömbben
	 */
	public function delete($id)
	{
		return $this->query->delete('id', '=', $id);
	}
	
}
?>