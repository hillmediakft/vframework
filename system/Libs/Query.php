<?php
namespace System\Libs;
use PDO;

/**
 *	Adatbázis lekérdezés kezelő osztály
 *	v1.5
 *
 *	Metódusok, beállítások:
 *	
 *	debug(); paraméter opcionalis - true|false (default false)
 *	reset(); nincs paraméter (visszaállítja a tulajdonságok alapértékét)	
 *	count(); paraméter: tábla neve (string) - Lekérdezi a paraméterben megadott tábla rekordjainak számát	
 *	found_rows();	visszaadja az előző SELECT SQL_CALC_FOUND_ROWS opcióval lekérdezett sorok számát (akkor kell, ha limit van beállítva)	
 *  set_default_table();  beállít egy default táblát a lekérdezésekhez. Ha nincs beállítva tábla a set_table() metódussal, akkor ez a tábla lesz a default 
 *
 *	Táblák megadása (string) (vesszővel elválsztva az oszlopnevek), vagy (array)
 *	1. (string) ebben az esteben nem lesz automatikusan backtick-elve a táblanév!! 	
 *		set_table('users, colors, lakhely');
 *	2. (array)	ebben az esteben backtick-elve lesznek az táblanevek!!		
 *		set_table(array('users','colors','lakhely'));
 *
 *	Lekérdezés során visszadott OSZLOPOK megadása (opcionális!):
 *	DEFAULT beállítás: '*' (vagyis nem muszáj megadni ezt a tulajdonságot)
 *	1. (string) ebben az esteben nem lesz automatikusan backtick-elve az oszlopnév!!... itt lehet valamilyen valamilyen sql függvényt használni
 *		set_columns( 'COUNT(*)' );
 *		set_columns( 'MAX(datum)' );
 *	2. (array)	ebben az esteben backtick-elve lesznek az oszlopnevek!!
 *		set_columns(array('user_id', 'user_name'));
 *
 *	LIMIT és offset megadása (integer)
 *		set_limit(3);
 *		set_offset(2);
 *
 *	ORDER BY megadása (2 paraméterrel dolgozik, de a második opcionális (itt lehet megadni kitételt pl.: DESC))
 *	Az 1. paraméter Tömb, a 2. paraméter String  (backtickelve lesznek az oszlopnevek)
 *		set_orderby(array('vezeteknev','utonev'));
 *		set_orderby(array('vezeteknev','utonev'), 'DESC');
 *	Az 1. paraméter String, a 2. paraméter String (NEM lesznek backtickelve az oszlopnevek)
 *		set_orderby('user_color, utonev');
 *		set_orderby('user_color, utonev', 'DESC');
 *
 *	WHERE feltétel megadási lehetőségek
 *		1. csak egy string
 *			set_where('vezeteknev');
 *			set_where('AND (');
 *			set_where(')');
 *	
 *		2. első tag (string) - operátor (string) - második tag (string)  (ilyenkor a kötőszó az AND)
 *			set_where('vezeteknev', '=', 'Bugyi'); 
 *
 *		3. első tag (string) - operátor (string) - második tag (string) - kötőszó (string) (AND, OR ...stb.) (a kötőszó paraméter elhagyható: default az AND)
 *			set_where('vezeteknev', '=', 'Bugyi', 'or');
 *	
 *		4. első tag (string) - operátor (string) - második tag (array - számmal indexelt (lehet asszociatív is, de csak az értékek lesznek használva)) -
 *		kötőszó (string) (AND, OR ...stb.) (a kötőszó paraméter elhagyható: default az AND)
 *			
 *			set_where('name', '=', array('sanyi','ede','egon','huba','feri'));
 *		
 *		  IN vagy NOT IN operátorral:			
 *			set_where('color', 'in', array('piros','zold','kek','barna'));
 *			set_where('color', 'not in', array('piros','zold','kek','barna'), 'or');
 *
 *		  BETWEEN vagy NOT BETWEEN operátorral (az adatot tartalmazó tömbnek 2 elemet kell tartalmaznia!!!):
 *			set_where('ertek', 'between', array('20','55'));
 *			set_where('date', 'between', array('2016-01-04-19:41', '2016-05-08-20:08'));
 *			set_where('ertek', 'not between', array('3200','15600'));
 *	
 *		5. első tag (null) - operátor (string) - második tag (array - asszociatív) - kötőszó (string) (AND, OR ...stb.) (a kötőszó paraméter elhagyható: default az AND)
 *			set_where(null, '!=', array('vezeteknev' => 'Bugyi', 'utonev' => 'sanyi', 'lakohyely' => 'Budapest'), 'and');
 *
 *	JOIN megadása - join típusa (string), táblanév (string), első tag (string), operátor (string), második tag (string)
 *		3 paraméteres eset (a 3. paraméterben teszőleges feltétel megadható):
 *			set_join('left', 'colors', 'users.user_color = colors.color_id');
 *		normál eset (5 paraméter):
 *			set_join('left', 'colors', 'users.user_color', '=', 'colors.color_id');
 *	
 *	INSERT
 *		paraméter - asszociatív tömb (kulcs: oszlop neve, érték: a mező értéke) 
 *		return integer||false (az új sor id-jével tér vissza vagy false)
 *
 *	UPDATE
 *		1. paraméter - asszociatív tömb (kulcs: oszlop neve, érték: a mező értéke)
 *			Ezek a kulcs->érték párok helyörzősen kerülnek az sql parancsba!	
 *		2. paraméter opcionális - asszociatív tömb (kulcs: oszlop neve, érték: a mező értéke)
 *			Ezek a kulcs->érték párok direkt módon (vagyis nem helyörzősen) kerülnek be az sql parancsba!
 *			pl.: $arr['user_failed_logins'] = 'user_failed_logins+1'; (mező értékének növeléséhez)
 *		return integer||false (az update-elt rekordok számával tér vissza vagy false)
 *
 *		Mindenképpen meg kell adni where feltételt (minden where beállítás működik)!!!
 *			set_where('user_id', '=', 24);		
 *			
 *		Minták:
 *			$arr = array('vezeteknev' => 'Poplacsek', 'utonev' => 'Elemer', 'user_color' => 44, 'user_lakohely' => 55)
 *			update($arr);
 *
 *			$arr = array('vezeteknev' => 'Poplacsek', 'utonev' => 'Elemer', 'user_color' => 44, 'user_lakohely' => 55)
 *			$fix_arr = array('user_failed_logins' => 'user_failed_logins+1')
 *			update($arr, $fix_arr);
 *
 *			Ha csak mező értékét akarjuk növelni, az első paraméter legyen üres tömb!!: 
  *			$fix_arr = array('user_failed_logins' => 'user_failed_logins+1')
 *			update(array(), $fix_arr);
 *			
 *	DELETE
 *		1. ha beállítunk where feltételt, vagy feltételeket (a delete metódust paraméterek nélkül kell meghívni!)
 *			set_where('user_id', '=', '12');
 *			delete();
 *	
 *		2. (ha nincs megadva where feltétel) első tag (string) - operátor - második tag (string vagy integer) 
 *			delete('user_id', '=', '12'));
 *
 *		return integer||false (a törölt rekordok számával tér vissza vagy false) 
 */ 
class Query {

				/**
				 * List of comparison types
				 *
				 * @access private
				 * @var array
				 */
//				private $comparisonTypes = array('=', '!=', '<>', '>', '<', '>=', '<=', '<=>', 'IS', 'IS NOT', 'IS NULL', 'IS NOT NULL', 'IN', 'NOT IN', 'ISNULL', 'LIKE', 'BETWEEN');

				/**
				 * List of join types
				 *
				 * @access private
				 * @var array
				 */
//				private $joinTypes = array('left', 'inner', 'cross', 'right', 'natural', 'straight');

	/**
	 *	Adatbázis kapcsolat objektuma
	 */
	private $connect;

	/**
	 * Alap tábla neve, ezt használja a lekérdezésekhez, ha nincs beállítva érték a $table tulajdonsághoz
	 * @access private
	 * @var String $default_table
	 */
	public $default_table;	

	/**
	 * @access private
	 * @var String or Array $table
	 */
	private $table = null;

	/**
	 * @access private
	 * @var String or Array $columns
	 */
	private $columns = '*';

	/**
	 * @access private
	 * @var String or Array $joins
	 */
	private $joins = null;

	/**
	 * @access private
	 * @var Array $where
	 */
	private $where = array();

	/**
	 * @access private
	 * @var String $orderby
	 */
	private $orderby = null;

	/**
	 * @access private
	 * @var int $limit
	 */
	private $limit = null;

	/**
	 * @access private
	 * @var int $offset
	 */
	private $offset = null;

	/**
	 * Ebbe a tömbbe kerülnek (átmenetileg) a lekérdezésbe kerülő adatok
	 *
	 * @access private
	 * @var Array $bindings
	 */
	private $bindings = array();
	
	// SQL parancs és attribútumok tesztelése
	private $debug = false;	

	/**
	 * Adatok visszaadásának formátumát tárolja 
	 */
	private $fetchmode = PDO::FETCH_ASSOC;

	/**
	 * Adatok visszaadási formátumának beállítása
	 * @param string $mode 	(assoc, object, both)
	 */
	public function setFetchMode($mode)
	{
		switch ($mode) {
			case 'assoc':
				$this->fetchmode = PDO::FETCH_ASSOC;
				break;

			case 'object':
				$this->fetchmode = PDO::FETCH_OBJ;
				break;

			case 'both':
				$this->fetchmode = PDO::FETCH_BOTH;
				break;
			
			default:
				$this->fetchmode = PDO::FETCH_ASSOC;
				break;
		}
	}

	/**
	 * Constructor (elindítja (illetve megkapja) az adatbáziskapcsolatot)
	 *
	 * @access public
	 * @param object $db_connect 		(adatbázis kapcsolat objetum) 		
	 */
	function __construct($db_connect)
	{
		$this->connect = $db_connect;
	}
	
	/**
	 * Alap tábla nevet állít be a lekérdezésekhez
	 *
	 * @access public
	 * @param string $default
	 */
	public function set_default_table($default)
	{
		$this->default_table = $default;
	}

	/**
	 * A lekérdezés string és adatok kiíratása
	 */
	public function debug($value = true)
	{
		$this->debug = (bool)$value;
	}
	
	/**
	 *	Megvizsgálja, hogy a where tömb utolsó eleme tartalmaz-e ( karaktert
	 *	ha igen, akkor true-t ad vissza
	 *	ha a where tömb üres, vagy az utolsó eleme nem tartalamz ( jelet false-t ad vissza
	 *
	 *	@return	boolean
	 */
	private function check_bracket()
	{
		if(!empty($this->where)){
			//az utolsó elemre állítjuk a where tömb belső mutatóját, hogy megkapjuk az utolsó elemét
			end($this->where);
			//itt visszadjuk az utolsó elemet egy átmeneti változóba
			$last = pos($this->where);
			//visszaállítjuk a tömb belső mutatóját az első elemre
			reset($this->where);
			
			// visszadjuk az utolsó tömbelem utolsó karakterét
			$result = substr($last, -1);
			// megvizsgáljuk, hogy az utolsó karakter ( karakter-e ... (ha igen, akkor true-t ad vissza a metódus)
			if($result == '('){
				return true;
			}
			else {
				return false;
			}
		}
		else{
			return false;
		}
	}
	
	/**
	 *	String Backtick-elése
	 *	Ha a kapott stringben nincs pont (pl.: users) akkor csak egyszerűen backtick-el (`users`)
	 *	Ha a kapott stringben van pont (pl.: users.user_name) akkor szétszedi és minden részt backtick-el (`users`.`user_name`)
	 *
	 *	@param	String	$string
	 *	@return	String
	 */
	private function add_backtick($string)
	{
		if(strpos($string, '.') !== false){
			//felbontjuk a stringet tömbelemekre
			$temp_arr = explode('.', $string); 
				//a bömbelemeket backtickeljük
				foreach($temp_arr as &$v) {
					if ($v !== '*') {
						$v = '`' . $v . '`'; 
					}
				}
			//összefőzzük a backtickelt ideiglenes tömbelemeket string-gé . karakterrel	
			$string = implode('.', $temp_arr); 			
		}
		else {
			$string = '`' . $string . '`'; 
		}
		return $string;
	} 
	
	
	/**
	 *	Visszaállítja az alapértékeket
	 */
	public function reset()
	{
		$this->table = null;
		$this->columns = '*';
		$this->joins = null;
		$this->orderby = null;
		$this->limit = null;
		$this->offset = null;
		$this->where = array();
		$this->bindings = array();
		$this->debug = false;
	}
	
	/**
	 * Tábla nevének beállítása
	 *
	 * @access public
	 * @param String or Array $tablename
	 * @return void
	 */
	public function set_table($tablename)
	{
		if(is_array($tablename)) {
			foreach($tablename as &$value) {
  				$value = $this->add_backtick($value); 
	        }
			$this->table = implode(',', $tablename);
		}
		elseif(is_string($tablename)) {
			$this->table = $tablename;
		}
		else {
			throw new \Exception('Nem megfelelo tipusu parameter lett atadva a query osztaly set_table() metodusanak!');
			exit;		
		}
	}

	/**
	 * Visszaadott oszopok beállítása
	 *
	 * @access public
	 * @param String or Array $columns
	 * @return void or false
	 */
	public function set_columns($columns = '*')
	{
		if(is_array($columns)) {
			foreach($columns as &$value) {
				$value = $this->add_backtick($value);
			}
			//string-gé alakítjuk a tömböt
			$this->columns = implode(',', $columns);
		}
		elseif(is_string($columns)){
			//ha string a paraméter, akkor nem backtick-elünk
			$this->columns = $columns;
		}
		else {
			throw new \Exception('Nem megfelelo tipusu parameter lett atadva a query osztaly set_columns() metodusanak!');
			exit;
		}
	}	

	/**
	 * WHERE feltétel megadása
	 * 
	 * @access public
	 * @param String or (null or false) $column
	 * @param String $oper
	 * @param String or Array $data
	 * @return void or false
	 */
	public function set_where($column, $oper = null, $data = null, $type = 'and')
	{
	//első parameter string és van operátor, és van data, ami string, vagy integer tipusú
		if(is_string($column) && !is_null($oper) && (is_string($data) || is_int($data))) {
			$string = strtoupper($type) . ' ' . $column . ' ' . $oper . ' ' . "?";
			$this->bindings[] = $data;
		
			//megvizsgáljuk, hogy zárójelben lesz-e a feltétel (és ennek megfelelően vágunk belőle)
			if($this->check_bracket()) {
				$string = ltrim($string, "ANDOR");
				$string = ltrim($string);
				$this->where[] = $string;
			}
			else {
				$this->where[] = $string;
			}
		}
		
	//csak egy string a paraméter
		elseif(is_string($column) && is_null($oper) && is_null($data)) {
			$this->where[] = $column;
 		} 
		
	//első parameter string és van operátor és van data (ami egy tömb! - számmal indexelt)
		elseif(is_string($column) && !is_null($oper) && is_array($data)) {
	
			$string = '';

				// IN vagy NOT IN operátor esetén
				if($oper == 'in' || $oper == 'not in'){
					$data_number = count($data);
					$substring = '(';
					for ($i=0; $i < $data_number; $i++) { 
						$substring .= '?';
						if($i < $data_number-1) {
							$substring .= ',';
						}
					}
					$substring .= ')';

					$string .= ' ' . strtoupper($type) . ' ' . $column . ' ' . strtoupper($oper) . ' ' . $substring;
				
					// adatok a bindings tömbbe
					foreach($data as $v) {
						$this->bindings[] = $v;
					} 

				}
				// BETWEEN vagy NOT BETWEEN operátor esetén
				elseif ($oper == 'between' || $oper == 'not between') {

					$string .= ' ' . strtoupper($type) . ' ' . $column . ' ' . strtoupper($oper) . ' ' . '? AND ?';

					$this->bindings[] = $data[0];
					$this->bindings[] = $data[1];
					
				}
				// Ha az operátor = vagy !=
				else {
					foreach($data as $v) {
						$string .= ' ' . strtoupper($type) . ' ' . $column . ' ' . $oper . ' ' . '?';
						$this->bindings[] = $v;
					} 
				}
			
			//megvizsgáljuk, hogy zárójelben lesz-e a feltétel (és ennek megfelelően vágunk belőle)
			if($this->check_bracket()) {
				$string = ltrim($string);
				$string = ltrim($string, "ANDOR");
				$string = ltrim($string);
				$this->where[] = $string;
			}
			else {
				$this->where[] = ltrim($string);
			}
		}
		
	//első parameter null, vagy false - van operátor - és van $data ami egy tömb (asszociatív) 
		elseif((is_null($column) || $column === false) && !is_null($oper) && is_array($data)) {

			$string = '';
			
			foreach($data as $k => $v) {
				$string .= ' ' . strtoupper($type) . ' ' . $k . ' ' . $oper . ' ' . "?";
				$this->bindings[] = $v;
			} 
			//megvizsgáljuk, hogy zárójelben lesz-e a feltétel (és ennek megfelelően vágunk belőle)
			if($this->check_bracket()) {
				$string = ltrim($string);
				$string = ltrim($string, "ANDOR");
				$string = ltrim($string);
				$this->where[] = $string;
			}
			else {
				$this->where[] = ltrim($string);
			}
		}
		else {
			return false;
		}
	}
	
	/**
	 * JOIN beállítása
	 *
	 * @access public
	 * @param String $type - JOIN típusa
	 * @param String $table - tábla neve
	 * @param String $first - ON feltétel első tagja
	 * @param String $oper - ON feltétel operátora
	 * @param String $second - ON feltétel második tagja
	 * @return void
	 */
	public function set_join($type, $table, $first, $oper = null, $second = null)
	{
		if(is_null($oper) && is_null($second)){
			$this->joins[] = strtoupper($type) . ' JOIN ' . $table . ' ON ' . $first;
		}
		else {
			$this->joins[] = strtoupper($type) . ' JOIN ' . $this->add_backtick($table) . ' ON ' . $this->add_backtick($first) . ' ' . $oper . ' ' . $this->add_backtick($second);
		}
	}

	/**
	 * LIMIT értékének beállítása
	 *
	 * @access public
	 * @param String $limit
	 * @return void
	 */
	public function set_limit($limit = 10)
	{
		$this->limit = $limit;
	}

	/**
	 * (LIMIT) offset értékének beállítása
	 *
	 * @access public
	 * @param String $offset
	 * @return void
	 */
	public function set_offset($offset = 1)
	{
		$this->offset = $offset;
	}	
	
	/**
	 * ORDER BY értékének beállítása
	 *
	 * @access public
	 * @param Array $columns (mezőnevek)
	 * @param String $exposure (kitétel pl.: DESC vagy ASC)
	 * @return void
	 */
	public function set_orderby($columns, $exposure = null)
	{
		if(is_array($columns)) {
			foreach($columns as &$value) {
				$value = $this->add_backtick($value); 
			}
			// ha még nincs orderby parancs
			if(empty($this->orderby)){
				$this->orderby = implode(',', $columns);
			} else {
				$this->orderby .= ', ' . implode(',', $columns);
			}
			// hozzáadjuk a kitételt
			if(!is_null($exposure)){
				$this->orderby .= ' ' . strtoupper($exposure);
			}
		}
		elseif(is_string($columns)) {
			
			if(empty($this->orderby)){
				$this->orderby = $columns;	
			} else {
				$this->orderby .= ', ' . $columns;	
			}
			// hozzáadjuk a kitételt
			if(!is_null($exposure)){
				$this->orderby .= ' ' . strtoupper($exposure);
			}
		}
		else {
			throw new \Exception('Nem megfelelo tipusu parameter lett atadva a query osztaly set_orderby() metodusanak!');
			exit;		
		}
	}
	
	
//--------------------------------------------------------------------------------------

	/**
	 * SELECT lekérdezés
	 *
	 * @access public
	 * @return array
	 */
	public function select()
	{
		if(is_null($this->table) && is_null($this->default_table)){
			throw new \Exception('Nincs beallitva tabla a lekerdezeshez!');
			exit;
		}
		
		// alaptábla a lekérdezésbe, ha nincs külön beállítva táblanév
		$tablename = (!is_null($this->table)) ? $this->table : $this->add_backtick($this->default_table); 

		$sql = "SELECT " . $this->columns . " FROM " . $tablename . $this->getJoins() . $this->getWhere() . $this->getOrderby() . $this->getLimit();
		
	    // Prepare to be executed
		$sth = $this->connect->prepare($sql);
		
		// Adatok megadása bindParam metódus meghívással
		/*
		for($i = 1; $i <= count($this->bindings); $i++) {
			$sth->bindParam($i, $this->bindings[$i-1]);
		}
		*/

			//sql parancs és attribútumok tesztelése
			if($this->debug === true) {
				echo '<pre>';
				print_r($sth);
				echo '</pre>';
				var_dump($this->bindings);
				exit;
			}	

		// lekérdezés végrehajtása (development környezetben hiba esetén kivételt dob)
		$result = $sth->execute($this->bindings);
		// beállítások alapértékre állítása
		$this->reset();

		if(!$result) {
			return false;
		}

		// return all fetched
		return $sth->fetchAll($this->fetchmode);
	}	

	/**
	 * INSERT lekérdezés
	 *
	 * @access public
	 * @param array $attributes
	 * @return integer|false	(az új sor id-jével tér vissza vagy false)
	 */
	public function insert($attributes = array())
	{
		if(is_null($this->table) && is_null($this->default_table)){
			throw new \Exception('Nincs beallitva tabla a lekerdezeshez!');
			exit;
		}
				
		$sql = $this->getInsert($attributes);
		// lekérdezés előkészítése 
		$sth = $this->connect->prepare($sql);

		// ':' jelek berakása a kulcsok elé 
		$attributes = $this->_key_colon($attributes);
		
			//sql parancs és attribútumok tesztelése
			if($this->debug === true) {
				echo '<pre>';
				print_r($sth);
				echo '</pre>';
				var_dump($attributes);
				exit;
			}		

		// Lekérdezés végrehajtása (development környezetben hiba esetén kivételt dob)
		$result = $sth->execute($attributes);
		// beállítások alapértékre állítása
		$this->reset();

		if(!$result) {
			return false;
		}
		
		// a last insert id -vel tér vissza
		return (int)$this->connect->lastinsertid();
	}
			
	/**
	 * UPDATE lekérdezés
	 * 
	 * @access public
	 * @param  array $attributes (lehet üres, akkor nem kerülnek az sql parancsba helyörzős attribútumok! (csak a where elembe))
	 * @param  array $fix_attributes opcionális (nem helyörzős attribútumok, például mező értékének növeléséhez!)
	 * @return bools
	 */
	public function update($attributes, $fix_attributes = array())
	{
		// megvizsálja, hogy van-e beállítva tábla vagy where feltétel
		if((is_null($this->table) && is_null($this->default_table)) || empty($this->where)){
			throw new \Exception('Nincs beallitva tabla, vagy WHERE feltetel az UPDATE lekerdezeshez!!');
			exit;
		}

		$sql = $this->getUpdate($attributes, $fix_attributes);
		
		// összeolvasztjuk az attributumok (csak értékek!) és a bindings tömböt egy számmal indexelt tömbbe, hogy passzoljanak a ?-es helyörzőkhöz	
		$attributes = array_merge(array_values($attributes), $this->bindings); 
				
		// Előkészítjük a lekérdezést 
		$sth = $this->connect->prepare( $sql );
			
			//sql parancs és attribútumok tesztelése
			if($this->debug === true) {
				echo '<pre>';
				print_r($sth);
				echo '</pre>';
				var_dump($attributes); var_dump($fix_attributes);
				exit;
			}
	
		// Lekérdezés végrehajtása (development környezetben hiba esetén kivételt dob)
		$result = $sth->execute( $attributes );
		// beállítások alapértékre állítása
		$this->reset();		
		
		if(!$result) {
			return false;
		}
		
		// visszatér az update-elt sorok számával
		return $sth->rowCount();
	}

	/**
	 * DELETE lekérdezés
	 *
	 * @access public
	 * @param String $column
	 * @param String $oper
	 * @param Int or String $data
	 * @return Integer or false
	 */
	public function delete($column = null, $oper = null, $data = null)
	{
		// megvizsálja, hogy van-e beállítva tábla
		if(is_null($this->table) && is_null($this->default_table)){
			throw new \Exception('Nincs beallitva tabla a lekerdezeshez!');
			exit;
		}
		
		// ha nincs beállítva where feltétel (a delete metódus kapja az adatokat)
		if(empty($this->where) && !is_null($column) && !is_null($oper) && !is_null($data)){

			$sql = $this->getDelete($column, $oper);
				
			// Lekérdezés előkésítése
			$sth = $this->connect->prepare($sql);
			// Adat társítása a helyörzőhöz
			$sth->bindParam(1, $data);
			
				//sql parancs és attribútumok tesztelése
				if($this->debug === true) {
					echo '<pre>';
					print_r($sth);
					echo '</pre>';
					var_dump($data);
					exit;
				}			
			
			// Lekérdezés végrehajtása (development környezetben hiba esetén kivételt dob)
			$result = $sth->execute();
			// beállítások alapértékre állítása
			$this->reset();			
			
			if(!$result) {
				return false;
			}

			// visszatér a törölt sorok számával (lehet 0 is)
			return $sth->rowCount();
		}
		// ha van beállítva where feltétel
		elseif(!empty($this->where) && is_null($column) && is_null($oper) && is_null($data)) {
			
			$sql = $this->getDelete();

			// Prepare to be executed
			$sth = $this->connect->prepare($sql);
			
			// Bind parameters
			/*
			for($i = 1; $i <= count($this->bindings); $i++) {
				$sth->bindParam($i, $this->bindings[$i-1]);
			}
			*/
			
				//sql parancs és attribútumok tesztelése
				if($this->debug === true) {
					echo '<pre>';
					print_r($sth);
					echo '</pre>';
					var_dump($this->bindings);
					exit;
				}			
			
			// Lekérdezés végrehajtása (development környezetben hiba esetén kivételt dob)
			$result = $sth->execute($this->bindings);
			// beállítások alapértékre állítása
			$this->reset();			

			if(!$result) {
				return false;
			}

			// Visszadja a törölt sorok számát
			return $sth->rowCount();
		}
		else {
			throw new \Exception('Rossz parameterezes, vagy beallitas a query class delete() metodusanak meghivasakor!');
			exit;			
		}
	}

	/**
	 * Visszadja a teljes INSERT stringet
	 *
	 * @access private
	 * @param Array $attributes
	 * @return String
	 */
	private function getInsert($attributes = array())
	{
		// alaptábla a lekérdezésbe, ha nincs külön beállítva táblanév
		$tablename = (!is_null($this->table)) ? $this->table : $this->add_backtick($this->default_table);
		
		$keys = array_keys($attributes);
		
		return "INSERT INTO ". $tablename . "(". implode("," , $keys  ) .")" . " VALUES(:". implode(",:", $keys  ) .")";		
	}

	/**
	 * Visszadja a teljes UPDATE stringet
	 *
	 * @access private
	 * @param array $attributes
	 * @param array $fix_attributes (nem helyörzős attribútumok, például mező értékének növeléséhez!)
	 * @return String
	 */
	private function getUpdate($attributes, $fix_attributes)
	{
		// alaptábla a lekérdezésbe, ha nincs külön beállítva táblanév
		$tablename = (!is_null($this->table)) ? $this->table : $this->add_backtick($this->default_table);		

		// nem helyörzős elemek hozzáadása
		$fix_element = '';
		if(is_array($fix_attributes) && !empty($fix_attributes)){
			foreach($fix_attributes as $key => $value) {
				$fix_element .= '`' . $key . '` = ' . $value . ', ';
			}
			if(empty($attributes)){
				$fix_element = rtrim($fix_element, ', ');
			}
		}
	
		$updates = '';
		foreach ($attributes as $key => $value)
		{
			$updates .= '`' . $key . '` = ?, ';
		}
		$updates = rtrim($updates, ', ');

		return 'UPDATE '. $tablename . ' SET ' . $fix_element . $updates . $this->getWhere();
	}

	/**
	 * Visszadja a teljes DELETE stringet
	 *
	 * @access private
	 * @param String $column
	 * @param Int $value
	 * @return String
	 */
	private function getDelete($column = null, $oper = null)
	{
		// alaptábla a lekérdezésbe, ha nincs külön beállítva táblanév
		$tablename = (!is_null($this->table)) ? $this->table : $this->add_backtick($this->default_table);

		// ha nincs beállítva külön where feltétel
		if(empty($this->where) && !is_null($column) && !is_null($oper)){
			return "DELETE FROM ". $tablename . " WHERE " . $column . " " . $oper . " ?";
		}
		// ha a where tömb nem üres
		elseif(!is_null($this->where) && is_null($column) && is_null($oper)){
			return "DELETE FROM ". $tablename . $this->getWhere();
		}
		else {
			return '';
		}
	}

	/**
	 * Visszadja a WHERE feltételek stringjét
	 *
	 * @access private
	 * @return String
	 */
	private function getWhere()
	{
		// If where is empty return empty string
		if(empty($this->where)) {
			return '';
		}

		// Implode where pecices then remove the first AND or OR
		return " WHERE" . ltrim( implode(" ", $this->where), "ANDOR" );
	}

	/**
	 * Visszada a beállított JOIN stringet
	 *
	 * @access private
	 * @return String
	 */
	private function getJoins()
	{
		// ha nincs where feltétel, akkor üres stringet ad vissza
		if(is_null($this->joins)) {
			return '';
		}

		// Implode where pecices then remove the first AND or OR
		return " " . implode(" ", $this->joins);
	}

	/**
	 * Visszadja az ORDER BY stringet
	 *
	 * @access private
	 * @return String
	 */
	private function getOrderby()
	{
		if(is_null($this->orderby)) {
			return '';
		}
		
		return ' ORDER BY ' . $this->orderby;
	}

	/**
	 * visszadja a beállított LIMIT stringet (és offset-et)
	 *
	 * @access private
	 * @return String
	 */
	private function getLimit()
	{
		if(!is_null($this->limit)) {
			if(!is_null($this->offset)) {
				return " LIMIT " . $this->offset . "," . $this->limit;
			}
			return " LIMIT " . $this->limit;
		} else {
			return '';
		}
	}
	
	/**
	 *	Lekérdezi a paraméterben megadott tábla rekordjainak számát
	 *
	 *	@param	string	$table
	 *	@return	integer
	 */
	public function count($table)
	{
		$sth = $this->connect->query("SELECT COUNT(*) FROM `" . $table . "`"); 
		$result = $sth->fetch(PDO::FETCH_NUM);
		return (int)$result[0];
	}

	/**
	 *	SQL_CALC_FOUND_ROWS lekérdezés után
	 *	visszaadja LIMIT-el lekérdezett, de összes rekord számát
	 *
	 *	@return	integer
	 */
	public function found_rows()
	{
		$sth = $this->connect->query("SELECT FOUND_ROWS()"); 
		$result = $sth->fetch(PDO::FETCH_NUM);
		return (int)$result[0];
	}

	/**
	 *  A visszadott tömb kulcsai elé betesz egy ':' jelet.	
	 *
	 *  @param array $data
	 *  @return array 	
	 */
	private function _key_colon($arr)
	{
		foreach ($arr as $key => $value) {
			$temp[':' . $key] = $value;
		}
		return $temp; 
	}


} //osztaly vege
?>