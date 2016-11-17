<?php 
namespace System\Core;
use System\Libs\DI;
use System\Libs\Query;


class Model {

	public $connect; //adatbazis csatlakozas objektuma
	
	public $query; //adatbaziskezelő objektumot rendeljük hozzá 

	public $request; //request objektum

	/**
	 *	Default tábla név, amivel a query objektum dolgozni fog, ha nem adunk meg külön táblát a lekérdezéskor
	 *  Ha megadjuk a leszármazott modelben ezt a tulajdonságot, akkor az lesz a default tábla a query objektumban
	 *  Ha nem adunk meg a leszármazott modelben $tabla tulajdonságot, akkor a contoroller neve lasz a default tábla név.
	 */
	protected $table;
	
	/**
	 * Egyedi azonosító (id) oszlop neve
	 */
	protected $id = 'id';
	

	function __construct()
	{
		// adatbáziskapcsolat létrehozása
		$this->connect = DI::get('connect');
		// request objektum		
		$this->request = DI::get('request');
		
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
		$this->connect = null;
	}

    /**
     * Jelszó kompatibilitás library betöltése
     *
     * @access private
     */
    public function load_password_compatibility()
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
	 *
	 *		'where' => array('color', '=', 'red');	
	 *
	 *		'where' => array(
	 *				   	 array('color', '=', 'red'),
	 *				   	 array('name', '=', 'ede', or)
	 *				   );	
	 *
	 * 		'limit' => 5,
	 *		'offset' => 3,
	 *		'orderby' => array(array(vezeteknev), DESC)
	 *	);
	 * 
	 * @param	mixed	$args		egy tömb, amiben megadjuk a lekérdezés paramétereit
	 * @return 	array
	 */
	final public function find($args = null)
	{
		if (func_num_args() == 0) {
			return $this->query->select();
		}

		if (is_int($args)) {
			$this->query->set_where($this->id, '=', $args);
			$result = $this->query->select();
			return $result[0];
		}

		if (is_array($args)) {

			if(isset($args['table'])){
				$this->query->set_table($args['table']); 
			}
			if(isset($args['columns'])){
				$this->query->set_columns($args['columns']); 
			}
			if(isset($args['where'])){

				foreach ($args['where'] as $where_arr) {
					if (is_array($where_arr)) {
						if (isset($where_arr[3])) {
							$this->query->set_where($where_arr[0], $where_arr[1], $where_arr[2], $where_arr[3]);
						} else {
							$this->query->set_where($where_arr[0], $where_arr[1], $where_arr[2]);
						}
					} else {
						$this->query->set_where($args['where'][0], $args['where'][1], $args['where'][2]);
						break;
					}
				}

			}
			if(isset($args['limit'])){
				$this->query->set_limit($args['limit']); 
			}
			if(isset($args['offset'])){
				$this->query->set_offset($args['offset']); 
			}
			if(isset($args['orderby'])){
				list($columns, $exposure) = $args['orderby'];
				$this->query->set_orderby($columns, $exposure); 
			}
//$this->query->debug();			
			return $this->query->select();
		}

		throw new \Exception("Rossz parametert kapott a model find metodusa");
	}


/*
	/**
	 * Új rekord a táblába
	 */
	// public function insert($data)
	// {
	// 	return $this->query->insert($data);
	// }

	/**
	 * Rekord módosítása
	 */
	/*
	final public function update($id, $data)
	{
		$this->query->set_where($this->id, '=', $id);
		return $this->query->update($data);
	}
	*/

	/**
	 * Rekord módosítása
	 */
	// public function delete($id)
	// {
	// 	$this->query->set_where($this->id, '=', $id);
	// 	return $this->query->delete();
	// }


}//osztály vége
?>