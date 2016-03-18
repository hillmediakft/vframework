<?php 
class Pages_model extends Admin_model {

	/**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
	function __construct()
	{
		parent::__construct();
	}
	
	public function all_pages()
	{
		// a query tulajdonság ($this->query) tartalmazza a query objektumot
		$this->query->set_table(array('pages')); 
		$this->query->set_columns(array('page_id', 'page_title', 'page_metatitle', 'page_metadescription')); 
		return $this->query->select(); 
	}
	
	public function update_page($id)
	{
		$data['page_body'] = $this->request->get_post('page_body');
		$data['page_metatitle'] = $this->request->get_post('page_metatitle');
		$data['page_metadescription'] = $this->request->get_post('page_metadescription');
		$data['page_metakeywords'] = $this->request->get_post('page_metakeywords');

		// új adatok beírása az adatbázisba (update) a $data tömb tartalmazza a frissítendő adatokat 
		$this->query->reset();
		$this->query->set_table(array('pages'));
		$this->query->set_where('page_id', '=', $id);
		$result = $this->query->update($data);
			
		if($result == 1) {
            Message::set('success', 'page_update_success');
			return true;
		}
		if($result == 0) {
            Message::set('warning', 'Nem történt módosítás');
			return true;
		}
		if($result === false) {
            Message::set('error', 'unknown_error');
			return false;
		}

	}
	
	/**
	 *	Egy oldal adatait kérdezi le az adatbázisból (pages tábla)
	 *
	 *	@param	$id String or Integer
	 *	@return	az adatok tömbben
	 */
	public function page_data_query($id)
	{
		$this->query->reset();
		$this->query->set_table(array('pages'));
		$this->query->set_columns(array('page_id', 'page_title', 'page_body', 'page_friendlyurl', 'page_metatitle', 'page_metadescription', 'page_metakeywords'));
		$this->query->set_where('page_id', '=', $id);
		
		return $this->query->select();
	}
	
}
?>