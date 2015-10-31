<?php 
class Content_model extends Admin_model {

	/**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
	function __construct()
	{
		parent::__construct();
	}
	
	public function all_content()
	{
		// a query tulajdonság ($this->query) tartalmazza a query objektumot
		$this->query->set_table(array('content')); 
		$this->query->set_columns(array('content_id', 'content_name', 'content_title')); 
		$result = $this->query->select(); 
	
		return $result;
	}
	
	public function update_content($id)
	{
		$data['content_title'] = $_POST['content_title'];
		$data['content_body'] = $_POST['content_body'];
		

		// új adatok beírása az adatbázisba (update) a $data tömb tartalmazza a frissítendő adatokat 
		$this->query->reset();
		$this->query->set_table(array('content'));
		$this->query->set_where('content_id', '=', $id['id']);
		$result = $this->query->update($data);
				
		if($result) {
			Message::set('success', 'page_update_success');
            return true;
		}
		else {
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
	public function content_data_query($id)
	{
		$this->query->reset();
		$this->query->set_table(array('content'));
		$this->query->set_columns(array('content_id', 'content_name', 'content_title', 'content_body'));
		$this->query->set_where('content_id', '=', $id);
		
		return $this->query->select();
	}
	
}
?>