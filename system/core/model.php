<?php 

class Model {

	public $connect; //adatbazis csatlakozas objektuma
	
	public $query; //adatbaziskezelő objektumot rendeljük hozzá 

	public $registry; //registry objektum

	/**
	 *	Default tábla név, amivel a query objektum dolgozni fog, ha nem adunk meg külön táblát a lekérdezéskor
	 *  Ha megadjuk a leszármazott modelben ezt a tulajdonságot, akkor az lesz a default tábla a query objektumban
	 *  Ha nem adunk meg a leszármazott modelben $tabla tulajdonságot, akkor a contoroller neve lasz a default tábla név.
	 */
	protected $table;
	
	function __construct()
	{
		// adatbáziskapcsolat létrehozása
		$this->connect = db::get_connect();
		// hiba visszaadás beállítása a PDO objektumban a fejlesztői környezet alapján
		if(ENV == 'development'){
			$this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}		
		
		$this->registry = Registry::get_instance();
		
		$this->request = $this->registry->request;
		
		// hozzárendeljük a query tulajdonsághoz a Query objektumot
		// ez a query tulajdonság a gyerek model-ek bármelyik metódusában elérhető
		// megkapja paraméterként az adatbáziskapcsolatot
		$this->query = new Query($this->connect);
		//default tábla beállítása 
		$this->query->set_default_table($this->table);
	}

	function __destruct()
	{
		// adatbáziskapcsolat lezárása
		$this->connect = db::close_connect();
	}

    /**
     * Jelszó kompatibilitás library betöltése
     *
     * @access private
     */
    protected function load_password_compatibility()
    {
        if (version_compare(PHP_VERSION, '5.5.0', '<')) {
            // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
            // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
            require_once(LIBS . '/password_compatibility_library.php');
        }
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