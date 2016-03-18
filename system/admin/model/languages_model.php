<?php 
class Languages_model extends Admin_model {

	/**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
	function __construct()
	{
		parent::__construct();
	}
	
	public function get_language_data()
	{
		// a query tulajdonság ($this->query) tartalmazza a query objektumot
		$this->query->set_table(array('languages')); 
		$this->query->set_columns(); 
		$result = $this->query->select(); 
	
		return $result;
	}
	
	/**
	 * Módosítja az slider sorrendet a slider táblában
	 * Az Ajax hívás után az order változó $_POST['order']a következő formátumú: 
	 * slider[]=1&slider[]=3&slider[]=2 stb Ezt kell tömbbé alakítani a feldolgozáshoz
	 * 
	 * A sikeres módosításról kiírja az üzenetet, ezt az Ajax hívást indító Javascript kód fogadja
	 * és szúrja be a HTML kódba (amennyiben sikeres az Ajax művelet))
	 *	
	 * @return 	void
	 */
	public function save_language_text()
	{
		$text_code = $this->request->get_post('name');
		$id = $this->request->get_post('pk');
		$text = $this->request->get_post('value');

		$lang = substr($text_code, -2);
		$column = 'text_' . $lang;
		
		if(!empty($text)) {
			$this->query->reset();
			$this->query->set_table(array('languages'));
			$this->query->set_where('text_id', '=', $id);
			if($this->query->update(array($column => $text)) === false){ 
				echo '{"success": false, "msg": "Szerver hiba!!"}';
			}
			else {
				echo '{"success": true}';
			}
		}
		else {
			header('HTTP 400 Bad Request', true, 400);
			echo "Írjon be szöveget!";	
		}

		
	}
	

}

?>