<?php 
namespace System\Admin\Model;

use System\Core\AdminModel;

class NewsletterStat_model extends AdminModel {

	protected $table = 'stats_newsletters';
	protected $id = 'statid';

	/**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Adatok beírása a stats_newsletters táblába email küldés közben
	 */
	public function insertStat($data)
	{
		return $this->query->insert($data);		
	}					

	/**
	 * Adatok módosítása a stats_newsletters táblában email küldés közben
	 */
	public function updateStat($newsletter_id, $data)
	{
		$this->query->set_where('newsletter_id', '=', $newsletter_id);
		return $this->query->update($data);		
	}

	/**
	 * Visszaadja a legnagyob (legutolsó) statid-t
	 */
/*	
	public function selectStatId()
	{
		$this->query->set_columns('statid');
		$this->query->set_orderby('statid', 'DESC');
		$this->query->set_limit(1);
		$result = $this->query->select();
		return (int)$result[0]['statid'];
	}
*/
	
	/**
	 *	Visszaadja a newsletter_stats tábla tartalmát
	 */
	//public function newsletter_stats_query()
	public function selectStats()
	{
		$this->query->set_columns(array('statid', 'stats_newsletters.newsletter_id', 'sent_date', 'recepients', 'send_success', 'send_fail', 'email_opens', 'unsubscribe_count', 'error', 'newsletters.newsletter_name', 'newsletters.newsletter_subject' )); 
		$this->query->set_join('left', 'newsletters', 'stats_newsletters.newsletter_id', '=', 'newsletters.newsletter_id');
		$this->query->set_orderby('statid', 'DESC');
		return $this->query->select(); 
	}	
}
?>