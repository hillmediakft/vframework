<?php 
class Employer_model extends Admin_model {

	/**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 *	Az employer tábla minden adatát lekérdezi
	 *	Ha van paraméter, akkor csak egy sort
	 *
	 *	@param integer	$id  opcionális paraméter
	 *	@return array
	 */
	public function all_employer_query($id = null)
	{
		$this->query->reset();
		$this->query->set_table(array('employer'));
		$this->query->set_columns('*');
        if(!is_null($id)) {
            $this->query->set_where('employer_id', '=', $id);
        }
        
		return $this->query->select();
	}
	
	/**
	 * Lekérdezi a jobs táblából a job_employer_id oszlop tartalmát	
	 * Az egyes munkaadókhoz tartozó munkák számának meghatározásához
	 */
	public function job_counter_query()
	{
		$this->query->reset();
		$this->query->set_table(array('jobs'));
		$this->query->set_columns(array('job_employer_id'));
		return $this->query->select();
	}
	
	
	/**
	 *	Mukaadó hozzáadása
	 *
	 *	@return bool	
	 */
	public function insert_employer()
	{
		$data = $_POST;
		$error_counter = 0;
		
		if(empty($data['employer_name'])) {
			$error_counter++;
			Message::set('error', 'A munkaadó neve nem lehet üres!');
		}
		if(empty($data['employer_address'])) {
			$error_counter++;
			Message::set('error', 'A cím nem lehet üres!');
		}
		
		if($error_counter == 0) {
			
			$data['employer_name'] = htmlentities($data['employer_name'], ENT_QUOTES, "UTF-8");
			$data['employer_address'] = htmlentities($data['employer_address'], ENT_QUOTES, "UTF-8");
			$data['employer_contact_person'] = htmlentities($data['employer_contact_person'], ENT_QUOTES, "UTF-8");
			$data['employer_contact_tel'] = htmlentities($data['employer_contact_tel'], ENT_QUOTES, "UTF-8");
			$data['employer_contact_email'] = htmlentities($data['employer_contact_email'], ENT_QUOTES, "UTF-8");
			$data['employer_remark'] = htmlentities($data['employer_remark'], ENT_QUOTES, "UTF-8");
			
			$this->query->reset();
			$this->query->set_table(array('employer'));
			$this->query->insert($data);
			
			Message::set('success', 'Munkaadó sikeresen hozzáadva.');
			return true;			

		} else {
			return false;
		}
	}

	/**
	 *	Munkaadó módosítása
	 *
	 *	@return bool	 
	 */
	public function update_employer($id)
	{
		$data = $_POST;
		$error_counter = 0;
		
		if(empty($data['employer_name'])) {
			$error_counter++;
			Message::set('error', 'A munkaadó neve nem lehet üres!');
		}
		if(empty($data['employer_address'])) {
			$error_counter++;
			Message::set('error', 'A cím nem lehet üres!');
		}
		
		if($error_counter == 0) {
			
			$data['employer_name'] = htmlentities($data['employer_name'], ENT_QUOTES, "UTF-8");
			$data['employer_address'] = htmlentities($data['employer_address'], ENT_QUOTES, "UTF-8");
			$data['employer_contact_person'] = htmlentities($data['employer_contact_person'], ENT_QUOTES, "UTF-8");
			$data['employer_contact_tel'] = htmlentities($data['employer_contact_tel'], ENT_QUOTES, "UTF-8");
			$data['employer_contact_email'] = htmlentities($data['employer_contact_email'], ENT_QUOTES, "UTF-8");
			$data['employer_remark'] = htmlentities($data['employer_remark'], ENT_QUOTES, "UTF-8");
			
			$this->query->reset();
			$this->query->set_table(array('employer'));
			$this->query->set_where('employer_id', '=', $id);
			$this->query->update($data);
			
			Message::set('success', 'A munkaadó adatai sikeresen módosítva.');
			return true;			

		} else {
			return false;
		}
	}
	
	/**
	 *	Munkaadók törlése
	 */
	public function delete_employer()
	{
		// a sikeres törlések számát tárolja
		$success_counter = 0;
		// a sikertelen törlések számát tárolja
		$fail_counter = 0; 
		
		// Több munkaadó törlése
		if(!empty($_POST)) {
			$data_arr = $_POST;
			
			//eltávolítjuk a tömbből a felesleges elemeket	
			if(isset($data_arr['employer_length'])) {
				unset($data_arr['employer_length']);
			}
		} else {
		// egy munkaadó törlése (nem POST adatok alapján)
			if(!isset($this->registry->params['id'])){
				throw new Exception('Nincs id-t tartalmazo parameter az url-ben (ezert nem tudunk torolni id alapjan)!');
				return false;
			}
			//berakjuk a $data_arr tömbbe a törlendő felhasználó id-jét
			$data_arr = array($this->registry->params['id']);
		}

		// bejárjuk a $data_arr tömböt és minden elemen végrehajtjuk a törlést
		foreach($data_arr as $value) {
			//átalakítjuk a integer-ré a kapott adatot (id)
			$value = (int)$value;
				
			//munkaadó törlése	
			$this->query->reset();
			$this->query->set_table(array('employer'));
			//a delete() metódus integert (lehet 0 is) vagy false-ot ad vissza
			$result = $this->query->delete('employer_id', '=', $value);
			
			if($result !== false) {
				// ha a törlési sql parancsban nincs hiba
				if($result > 0){
					//sikeres törlés
					$success_counter += $result;
					
					//munkaadóhoz tartozó munkák törlése	
					$this->query->reset();
					$this->query->set_table(array('jobs'));									
					$this->query->delete('job_employer_id', '=', $value);									
				}
				else {
					//sikertelen törlés
					$fail_counter += 1;
				}
			}
			else {
				// ha a törlési sql parancsban hiba van
				throw new Exception('Hibas sql parancs: nem sikerult a DELETE lekerdezes az adatbazisbol!');
				return false;
			}
		}

		// üzenetek eltárolása
		if($success_counter > 0) {
			Message::set('success', $success_counter . ' munkaadó törlése sikerült.');	
		}
		if($fail_counter > 0){
			Message::set('error', $fail_counter . ' munkaadó törlése nem sikerült!');	
		}
		
		// default visszatérési érték (akkor tér vissza false-al ha hibás az sql parancs)	
		return true;	
	}
	
	/**
	 *	(AJAX) Az employer tábla employer_status mezőjének ad értéket
	 *	siker vagy hiba esetén megy vissza az üzenet a javascriptnek 	
	 *
	 *	@param	integer	$id	
	 *	@param	integer	$data (0 vagy 1)	
	 * 	@return void
	 */
	public function change_status_query($id, $data)
	{
		$this->query->reset();
		$this->query->set_table(array('employer'));
		$this->query->set_where('employer_id', '=', $id);
		$result = $this->query->update(array('employer_status' => $data)); 
	
		if($result) {
			echo json_encode(array("status" => 'success')); 	
		}
		else {
			echo json_encode(array("status" => 'error'));
		}		
	}	

}
?>