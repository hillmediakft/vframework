<?php 
class Testimonials_model extends Admin_model {

	/**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
	function __construct()
	{
		parent::__construct();
	}
	
	public function all_testimonials()
	{
		// a query tulajdonság ($this->query) tartalmazza a query objektumot
		$this->query->set_table(array('testimonials')); 
		$this->query->set_columns(array('id', 'text', 'name', 'title')); 
		$result = $this->query->select(); 
		return $result;
	}
	
	public function update_testimonial($id)
	{
		$data['name'] = $this->request->get_post('testimonial_name');
		$data['title'] = $this->request->get_post('testimonial_title');
		$data['text'] = $this->request->get_post('testimonial_text');

		// új adatok beírása az adatbázisba (update) a $data tömb tartalmazza a frissítendő adatokat 
		$this->query->reset();
		$this->query->set_table(array('testimonials'));
		$this->query->set_where('id', '=', $id);
		$result = $this->query->update($data);
				
		if($result >= 0) {
            Message::set('success', 'testimonial_update_success');
			return true;
		}
		else {
            Message::set('error', 'unknown_error');
			return false;
		}
	}
	
	/**
	 *	A testimonial táblából kérdez le adatokat
	 *
	 *	@param	$id String or Integer
	 *	@return	az adatok tömbben
	 */
	public function testimonial_data_query($id)
	{
		$this->query->reset();
		$this->query->set_table(array('testimonials'));
		$this->query->set_columns(array('id', 'text', 'name', 'title'));
		$this->query->set_where('id', '=', $id);
		return $this->query->select();
	}
	
	/**
	 *	Egy oldal adatait kérdezi le az adatbázisból (pages tábla)
	 *
	 *	@param	$id String or Integer
	 *	@return	az adatok tömbben
	 */
	public function insert_testimonial()
	{
		$data['name'] = $this->request->get_post('testimonial_name');
		$data['title'] = $this->request->get_post('testimonial_title');
		$data['text'] = $this->request->get_post('testimonial_text');
		
		$this->query->reset();
		$this->query->set_table(array('testimonials'));
		$result = $this->query->insert($data);

		if($result) {
            Message::set('success', 'new_testimonial_success');
			return true;
		}
		else {
            Message::set('error', 'unknown_error');
			return false;
		}
	}
	
	/**
	 *	Vélemény törlése a testimonials táblából
	 *
	 *	@param	$id String or Integer
	 *	@return	az adatok tömbben
	 */
	public function delete_testimonial($id)
	{
		$this->query->reset();
		$this->query->set_table(array('testimonials'));
		$this->query->set_where('id', '=', $id);
		$result = $this->query->delete();

		// ha sikeres a törlés 1 a vissaztérési érték
		if($result == 1) {
            Message::set('success', 'testimonial_delete_success');
			return true;
		}
		else {
            Message::set('error', 'unknown_error');
			return false;
		}
	}
	
}
?>