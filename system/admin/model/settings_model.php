<?php 
class Settings_model extends Admin_model {

	/**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Oldal szintű beállítások lekérdezése a settings táblából
	 *
	 * @return array a beállítások tömbje
	 */
	public function get_settings()
	{
		$this->query->set_table(array('settings')); 
		$this->query->set_columns('*'); 
		$result = $this->query->select();
        return $result[0];
	}
	
	/**
	 * Oldal szintű beállítások módosításának elmentése
	 *
	 * @return true/false
	 */
	public function update_settings()
	{
		$data['ceg'] = $this->request->get_post('setting_ceg');
		$data['cim'] = $this->request->get_post('setting_cim');
		$data['email'] = $this->request->get_post('setting_email');

		// új adatok beírása az adatbázisba (update) a $data tömb tartalmazza a frissítendő adatokat 
		$this->query->reset();
		$this->query->set_table(array('settings'));
		$this->query->set_where('id', '=', 1);
		$result = $this->query->update($data);
				
		if($result) {
            Message::set('success', 'settings_update_success');
			return true;
		}
		else {
            Message::set('error', 'unknown_error');
			return false;
		}
	}

}
?>