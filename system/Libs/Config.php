<?php
namespace System\Libs;

/**
 *	Config beállítások kezelése
 *
 *  Publikus metódusok:
 *      Config::load();    
 *      Config::set();    
 *      Config::get();    
 *
 *	LOAD - Config tömb betöltése fájlból (a fájlt a config mappából tölti be)
 *      1. par string: a file neve kiterjesztés nelkül
 *      2. par string opcionális: egy kulcs amihez az új elemek tartozni fognak
 *      Config::load('file_neve_kiterjesztés_nelkül');
 *      Config::load('file_neve_kiterjesztés_nelkül', 'kulcs');
 *
 *  SET - Értéket állít be, vagy módosít
 *      1. par string: kulcs amit beállítani, vagy módosítani akarunk
 *      2. par string: a kulcshoz tartozó érték
 *      Config::set('kulcs', 'érték');
 *
 *  GET - Értéket ad vissza a config tömbből
 *      Ha nem található a megadott kulcs akkor az opcionális második paraméter értékét adja vissza (ami alapbeállításon NULL)
 *	    Ha egyetlen paraméternek null-t adunk meg, akkor az egész config tömböt visszaadja
 *	    Példa a kulcsok megadására: (a többdimenziós tömb elemeit a . karakterrel elválasztva kell megadni, mintha egy útvonal lenne)
 *		Config::get('database');
 *		Config::get('database.params');
 *		Config::get('database.params.host');
 *      Második paraméterben megadható, hogy mit adjon vissza, ha nem található az 1. paraméterben megadott elem
 *		Config::get('database.params.host', 'default');
*/
class Config {
	// a config elemek ebben a tömbben tárolódnak
	private static $config = array();
	
	//betöltött config fájlok listája
	private static $is_loaded = array();

	// nem lehet klónozni, vagy a new kulcsszóval példányt létrehozni a config objektumból
	private function __construct() {}
	private function __clone() {}
	
	/**
	 *	Config tömb betöltése fájlból
	 *	(a fájlt a config mappából tölti be)
	 *
	 *	@param	string	$file	A betöltendő file neve (kiterjesztés nélkül)
	 *	@param	string	$index	Opcionális - Megadható egy kulcs, amihez tartozni fognak az új beállítások
	 */
	public static function load($file, $index = null)
	{
		// a file nevéből kivágjuk a .php stringet
		$file = str_replace('.php', '', $file);
		
		// beállítjuk a file elérési útját
		$file_path = CONFIG . '/' . $file . '.php';
        
		// megvizsgáljuk, hogy be van-e már töltve a file
		if(in_array($file_path, self::$is_loaded)){
			throw new \Exception('HIBA: A megadott config file mar be van toltve!');
		}
		
		if(!file_exists($file_path)){
			throw new \Exception('HIBA: A megadott config file nem toltheto be!');
		}
		
		// fájl betöltése (a file-nak tartalmaznia kell egy $config tömböt)
		include($file_path);
		
		if (!isset($config) OR !is_array($config)) {
			throw new \Exception('HIBA: A betoltott config file nem megfelelo!');
		}
				
		// berakjuk a file nevet a betöltött fájlokhoz
		self::$is_loaded[] = $file_path;
		
		// új elem hozzáadása a config tömbhöz
		self::add($config, $index);
		
		// betöltött "ideiglenes" tömbnek null értéket adunk
		$config = null;
	}
	
	/**
	 *	Új elemek hozzáadása a config tömbhöz
	 *	Az új elem a config tömb "gyökerébe" kerül
	 *
	 *	@param	array	$new_items	Tömb, amit hozzáadunk a confighoz
	 *	@param	string	$index a kulcs neve amihez hozzáadjuk az új tömböt
	 *	@return	void
	 */
	private static function add($new_items = array(), $index = null)
	{	
		// ha nem adunk meg indexet
		if(is_null($index)){
			self::$config = array_merge(self::$config, $new_items);
		} else {
			// ha megadunk indexet és az már létezik
			if(array_key_exists($index, self::$config)){
				//throw new \Exception('Ilyen nevu kulcs mar letezik a config elemben!');			
				foreach($new_items as $key => $value) {
					self::$config[$index][$key] = $value;
				} 
			} else {
				// ha megadunk indexet, de nincs még ilyen elem
				self::$config[$index] = $new_items;
			}
		}
	}
	
	/**
	 *	Értéket állít be, vagy módosít
	 *
	 *	@param String	$key	(kulcs amit beállítani, vagy módosítani akarunk)
	 *	@param Mixed	$value
	 *	@return void
	 */
	public static function set($key, $value)
	{
		self::set_array_value(self::$config, $key, $value);
	}	

	/**
	 *	Visszadja az config tömb $key kulcshoz tartozó értékét (ha nincs ilyen kulcs akkor a default változó értékét adja vissza)
	 *	Ha paraméternek null-t adunk meg, akkor az egész config tömböt visszaadja
	 *	Példa a kulcsok megadására: (a többdimenziós tömb elemeit a . karakterrel elválasztva kell megadni, mintha egy útvonal lenne)
	 *		Config::get('database');
	 *		Config::get('database.params');
	 *		Config::get('database.params.host');	 
	 *
	 *	@param	string	$key	tömbelem kulcsa, ami az adatot tartalmazza
	 *	@param	mixed	$default	ha nem található a megadott elem ezt adja vissza 
	 *	@return	mixed | null
	 */
	public static function get($key, $default = null)
	{
		return self::get_array_value(self::$config, $key, $default);
	}

	/**
	 * Set an array config (dot-notated) to the value.
	 *
	 * @param   array   $array  The array to insert it into
	 * @param   mixed   $key    The dot-notated key to set
	 * @param   mixed   $value  The value
	 * @return  void
	 */
	private static function set_array_value(&$array, $key, $value = null)
	{
		$keys = explode('.', $key);

		while (count($keys) > 1)
		{
			$key = array_shift($keys);

			if (!array_key_exists($key, $array))
			{
				$array[$key] = array();
			}

			$array =& $array[$key];
		}

		$array[array_shift($keys)] = $value;
	}	
	
	
	/**
	 *	Visszadja egy tömb megadott elemét
	 * 	Ha nincs a tömbben a megadott kulcs, akkor a default paraméterben megadott értékkel tér vissza	
	 *	Ha a második paraméter null, akkor az egész tömböt visszaadja	
	 *
	 *	Példa a kulcsok megadására: (a többdimenziós tömb elemeit a . karakterrel elválasztva kell megadni, mintha egy útvonal lenne)
	 *		get_array_value($array, 'database');
	 *		get_array_value($array, 'database.params');
	 *		get_array_value($array, 'database.params.host');
	 *
	 *	@param   array   $array    Ebben a tömbben keresünk
	 *	@param   mixed   $key      A kulcs (illetve kulcsok) amit keresünk (language vagy database.host)
	 *	@param   string  $default  Default érték
	 *	@return  mixed
	 */
	private static function get_array_value($array, $key, $default = null)
	{
		if (is_null($key))
		{
			return $array;
		}
		
		foreach (explode('.', $key) as $key_part)
		{
			if (!array_key_exists($key_part, $array))
			{
				return $default;
			}
			$array = $array[$key_part];
		}

		return $array;
	}	
} // config class vége
?>