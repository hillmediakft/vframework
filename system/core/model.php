<?php 

class Model {

	public $connect; //adatbazis csatlakozas objektuma
	
	public $query; //adatbaziskezelő objektumot rendeljük hozzá 

	public $registry; //registry objektum
	
	function __construct()
	{
		// adatbáziskapcsolat létrehozása
		$this->connect = db::get_connect();
		
		$this->registry = Registry::get_instance();
		
		$this->request = $this->registry->request;
		
		// hozzárendeljük a query tulajdonsághoz a Query objektumot
		// ez a query tulajdonság a gyerek model-ek bármelyik metódusában elérhető
		// megkapja paraméterként az adatbáziskapcsolatot
		$this->query = new Query($this->connect);
		
	}

	function __destruct()
	{
		// adatbáziskapcsolat lezárása
		$this->connect = db::close_connect();
	}

	
	/**
	 * Adat lekérdezése egy táblából 
	 *
	 *	PÉLDA:
	 *	$args = array(
	 *		'table' => array('jobs'),
	 *		'columns' => array('jobs', 'users', 'slider'),
	 *		'limit' => 5,
	 *		'offset' => 3,
	 *		'orderby' => array(array(vezeteknev), DESC)
	 *	);
	 * 
	 * @param	array	$args		egy tömb, amiben megadjuk a lekérdezés paramétereit
	 * @return 	array
	 */
	public function get_data($args = array())
	{
		$this->query->reset(); 
		
		if(isset($args['table'])){
			$this->query->set_table($args['table']); 
		}
		if(isset($args['columns'])){
			$this->query->set_columns($args['columns']); 
		} else {
			$this->query->set_columns('*'); 
		}
		if(isset($args['limit'])){
			$this->query->set_limit($args['limit']); 
		}
		if(isset($args['offset'])){
			$this->query->set_offset($args['offset']); 
		}
		if(isset($args['orderby'])){
			$this->query->set_orderby($args['orderby']); 
		}
			
		return $this->query->select();
	}	


}//osztály vége
?>