<?php 
class Testimonials_model extends Admin_model {

	protected $table = 'testimonials';

	/**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
	function __construct()
	{
		parent::__construct();
	}
	
	public function all_testimonials()
	{
		$this->query->set_columns(array('id', 'text', 'name', 'title')); 
		return $this->query->select(); 
	}
	
	public function update_testimonial($id)
	{
		$data['name'] = $this->request->get_post('testimonial_name');
		$data['title'] = $this->request->get_post('testimonial_title');
		$data['text'] = $this->request->get_post('testimonial_text');

		// új adatok beírása az adatbázisba (update) a $data tömb tartalmazza a frissítendő adatokat 
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
		$this->query->set_columns(array('id', 'text', 'name', 'title'));
		$this->query->set_where('id', '=', $id);
// $this->query->debug();		
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

		// input adatok tárolása session-ben
		Session::set('testimonial_input', $data);

		// validátor objektum létrehozása
        $validate = new Validate();

        // szabályok megadása az egyes mezőkhöz (mező neve, label, szabály)
        $validate->add_rule('name', 'név', array(
            'required' => true,
            'min' => 2
        ));
        $validate->add_rule('title', 'beosztás', array(
            'required' => true
        ));
        $validate->add_rule('text', 'vélemény', array(
            'required' => true
        ));

        // üzenetek megadása az egyes szabályokhoz (szabály_neve, üzenet)
        $validate->set_message('required', 'A :label mező nem lehet üres!');
        $validate->set_message('min', 'A :label mező túl kevés karaktert tartalmaz!');

        // mezők validálása
        $validate->check($data);

        // HIBAELLENŐRZÉS - ha valamilyen hiba van a form adataiban
        if(!$validate->passed()){
            foreach ($validate->get_error() as $value) {
                Message::set('error', $value);
            }
            return false;

        } else {

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
	}
	
	/**
	 *	Vélemény törlése a testimonials táblából
	 *
	 *	@param	$id String or Integer
	 *	@return	az adatok tömbben
	 */
	public function delete_testimonial($id)
	{
		$result = $this->query->delete('id', '=', $id);

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