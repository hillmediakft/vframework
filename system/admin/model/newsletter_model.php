<?php 
namespace System\Admin\Model;
use System\Core\Admin_model;

class Newsletter_model extends Admin_model {

	protected $table = 'newsletters';
	protected $id = 'newsletter_id';

	/**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 *	Visszaadja a newsletter tábla tartalmát
	 *	Ha kap egy id paramétert (integer), akkor csak egy sort ad vissza a táblából
	 *
	 *	@param $id Integer 
	 */
	public function selectNewsletter($id = null)
	{
		if(!is_null($id)){
			$id = (int)$id;
			$this->query->set_where('newsletter_id', '=', $id); 
		}
		return $this->query->select(); 
	}
	
	/**
	 * Visszaadja azokat a felhasználókat a users táblából, akik kérnek hírlevelet
	 */
	public function userEmails()
	{
		$this->query->set_table(array('users')); 
		$this->query->set_columns(array('user_name', 'user_email')); 
		$this->query->set_where('newsletter', '=', 1); 
		return $this->query->select(); 
	}
		
	/**
	 * hírlevél hozzáadása
	 */
	public function insert($data)
	{
		return $this->query->insert($data);
	}

	/**
	 * Hírlevél módosítása
	 */
	public function update($id, $data)
	{
		$this->query->set_where('newsletter_id', '=', $id);
		return $this->query->update($data);
	}

	/**
	 * DELETE
	 */
	public function delete($id)
	{
		return $this->query->delete('newsletter_id', '=', $id);
	}

	/**
	 * az adatbázisban módosítjuk az utolsó küldés mező tartalmát
	 */
	public function updateLastSentDate($newsletter_id, $lastsent_date)
	{
		$this->query->set_where('newsletter_id', '=', $newsletter_id);
		return $this->query->update(array('newsletter_lastsent_date' => $lastsent_date));
	}	
	
}
?>