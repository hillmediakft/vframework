<?php
namespace System\Libs;

/**
 *	Üzeneteket kezelő osztály v1.2
 *
 *	Az üzeneteket a $_SESSION['message'] elembe teszi, majd az üzenetek visszaadásakor törli
 *
 *	Publikus metódusok:
 *      Message::set_area(); - üzenetek modulhoz rendelése
 *      Message::load();  - üzeneteket tartalmazó tömb betöltése fájlból
 *		Message::set(); - üzenetek megadása
 *		Message::get(); - üzenetek visszaadása
 *		Message::clear(); - üzenetek törlése a $msg_all  és a $_SESSION tömbből
 *		Message::log(); - üzenetek naplózása log file-ba
 *		Message::show(); - üzenetek egyszerű visszaadása
 *
 *  Az üzenetek modulhoz rendelése: paraméter a modul neve pl.: admin vagy site
 *  Az üzenetek a $_SESSION['message_modulneve'] elembe fognak kerülni)
 *  Ha nem hívjuk meg ezt a metódust, akkor az üzenetek a $_SESSION['message'] elembe fognak kerülni (de ebben az esetben az esetben megjelenhetnek az üzenetek mindkét modulban!!)
 *      Message::set_area('admin');
 *
 *  Üzenetek tömb betöltése: Betölt egy üzeneteket tartalmazó nyelvi fájlt és hozzárendeli a $msg_all tömbhöz
 *  (Az üzeneteket tartalmazó file-ok nevei: valami-hu.php. Az első paraméterben csak így kell megadni: valami)
 *      Message::load('fájl_neve','nyelvi_kód');
 *
 *	Üzenet megadása (1. parameter: üzenet típusa, 2. paraméter: üzenet szövege):
 *  (Az üzenet szövege lehet a betöltött üzenet tömb valamelyik kulcsa, vagy lehet teljesen egyedi)
 *		Message::set('error', 'Leo in imperdiet erat nec pulvinar.'); 
 *
 *	Összes üzenet visszaadása (többtimenziós tömböt ad vissza pl.: $messages['uzenet_tipusa'][0] ):
 *		$messages = Message::get();
 *  
 *	Egy bizonyos típusú üzenet visszaadása (egydimenziós tömböt ad vissza pl.: $errors[0] ):
 *		$errors = Message::get('error');
 *
 *	Összes üzenet törlése: Ezt a metódust meg kell hívni az üzenetek visszaadása után, hogy törlődjenek a SESSION-ból az üzenetek!
 *		Message::clear();
 *	Meghatározott típusú üzenetek törlése:
 *		Message::clear('notice');
 *
 *  Visszaadja a paraméterként kapott üzenetet
 *      echo Message::show('unknown_error'); - kulcs az üzenetek tömbből
 *      echo Message::show('Bármilyen üzenet amit vissza akarunk küldeni a javascriptnek!');
 *
 */
class Message {
	
	/**
	 *	Az összes betöltött üzenetet tárolja
	 *	@var Array	
	 */    
    private static $msg_all = array();
    
    //betöltött üzenet fájlok listája
    private static $is_loaded = array();
    
	/**
	 *	Az aktív (beállított) üzeneteket tárolja
	 *	@var Array	
	 */
	private static $msg_active;

	/**
	 *	Az engedélyezett üzenet típusokat tárolja
	 *	@var Array
	 */
	private static $types_arr = array('success', 'error', 'info', 'warning');
    
    /**
     * A modul nevét tárolja (pl.: site vagy admin)
     */
    private static $area;
    
    
	// nem példányosítható objektum
	private function __construct() {}
	// nem klónozható objektum
	private function __clone() {}
    

	public static function init($filename, $lang_code)
	{
		self::$area = AREA;
		self::load($filename, $lang_code); 
	}


    /**
     * A modul nevének beállítása
     * (az üzenetek a $_SESSION['message_modulneve'] elembe fognak kerülni)
     * Ha nem hívjuk meg ezt a metódust, akkor az üzenetek a $_SESSION['message'] elembe fognak kerülni
     */
    public static function set_area($area){
        self::$area = $area;
    }
    
	/**
	 *	Betölti az üzeneteket tartalamzó fájlt
     *  A paraméterekben kapja meg, hogy melyik nyelvi fájlt kell betölteni
     *
     *  @param  string  $filename  A file nevének első része, - és nyelvi kód nélkül
     *  @param  string  $lang_code  nyelvi kód pl: en
	 *  @return void     
	 */
	public static function load($filename, $lang_code)
	{
		$msg_file = MESSAGE .'/' . $filename . '-' . $lang_code . '.php';
        
        // megvizsgáljuk, hogy be van-e már töltve a file
		if(in_array($msg_file, self::$is_loaded)){
			throw new \Exception('HIBA: A megadott uzenet file mar be van toltve!');
		}
        
		if (!file_exists($msg_file)) {
			throw new \Exception('HIBA: Nincs ilyen nyelvi file!');
		} 
		
        // fájl betöltése (a file-nak tartalmaznia kell egy $msg tömböt)
        include($msg_file);
        
		if (!isset($msg) OR !is_array($msg)) {
			throw new \Exception('HIBA: Az uzeneteket tartalamzo file nem megfelelo!');
		}
		
        // berakjuk a file nevet a betöltött fájlokhoz
		self::$is_loaded[] = $msg_file;
                
		// új elemek hozzáadása a $msg_all tömbhöz
		self::add($msg);
        unset($msg);
	}    
    
    /**
     *	Új elemek hozzáadása az $msg_all tömbhöz
     *	Az új elem a $msg_all tömb "gyökerébe" kerül
     *
     *	@param	array	$new_items	Tömb, amit hozzáadunk
     *	@return	void
     */
    private static function add($new_items)
    {
        self::$msg_all = array_merge(self::$msg_all, $new_items);
    }    
    
	/**
	 *	Üzenet megadása
	 *	Az üzenetet berakja a $_SESSION['message_modulneve']['üzenet_típus'] tömbelembe
     *  Ha az üzenet, mint kulcs benne van a $msg_all tömbben, akkor annak az értéke lesz az üzenet
	 *
	 *	@param String $type		az üzenet típusa 	
	 *	@param String $content	az üzenet szövege 	
	 *	@return void	
	 */
	public static function set($type, $content)
	{
        // üzenetek modulhoz rendelése pl.: message vagy message_site vagy message_admin
        $message_key = (empty(self::$area)) ? 'message' : 'message_' . self::$area;
        
        /*
        if(!isset($_SESSION[$message_key]])) {
            $_SESSION[$message_key] = array();    
        }
        */
        
		if(in_array($type, self::$types_arr)){
            if(array_key_exists($content, self::$msg_all)){
                $_SESSION[$message_key][$type][] = self::$msg_all[$content];
            } else {
                $_SESSION[$message_key][$type][] = $content;
            }
		} else {
			throw new \Exception('HIBA: nem definialt uzenet tipust adtunk meg a Message::set() metodusnak!');
		}
	}

	/**
	 *	Üzenet visszaadása
	 *
	 *	@param String	$type	a visszaadandó üzenet typusa (ha nincs paraméter, az összes üzenetet visszaadja)
	 *	@return	Array 
	 */
	public static function get($type = null)
	{
        $message_key = (empty(self::$area)) ? 'message' : 'message_' . self::$area;
        
		if (isset($_SESSION[$message_key])) {
			self::$msg_active = $_SESSION[$message_key];
			//$_SESSION[$message_key] = null;
			//unset($_SESSION['message']);
		}
		
		if (isset(self::$msg_active)){
			// megadott típusú üzenetek visszaadása
			if(!is_null($type)){
				// vizsgálat, hogy létezik-e ilyen típusú üzenet
				if(in_array($type, self::$types_arr)){
					// vizsgálat, hogy vannak-e ilyen típusú üzenetek
					if(array_key_exists($type, self::$msg_active)){
						// visszatér a megadott típusú üzeneteket tartalmazó tömbbel
						return self::$msg_active[$type];
					} else {
						// ha nincs ilyen típusú üzenet akkor a visszatérési érték üres tömb!
						return array();
					}
				} else {
					throw new \Exception('Hiba: Nem definialt uzenet tipust akarunk visszadni a Message:get() metodussal!');
				}
			} else {
			//összes típusú üzenet visszaadása
			return self::$msg_active;
			}
		} else {
			// ha nincs semmilyen üzenet
			return array();
		}
	}
	
	/**
	 *	Üzenetek törlése
	 *	Az üzenetek visszaadása után meg kell hívni ezt a metódust!
	 *
	 *	@param	String	$type	a törlendő üzenetek típusa
	 */
	public static function clear($type = null)
	{
        $message_key = (empty(self::$area)) ? 'message' : 'message_' . self::$area;
        
        if(is_null($type)){
            if(isset($_SESSION[$message_key])){
                $_SESSION[$message_key] = null;    
            }                
            if(isset(self::$msg_active)){
                self::$msg_active = null;
            }               
        } else {
            if(!in_array($type, self::$types_arr)){
                throw new \Exception('HIBA: nem definialt uzenet tipust adtunk meg a Message::clear() metodusnak!');
            }  
            if(isset($_SESSION[$message_key][$type])){
                unset($_SESSION[$message_key][$type]);
            }                
            if(isset(self::$msg_active[$type])){
                unset(self::$msg_active[$type]);
            }                
        }      
	}
    
    /**
     *  Visszaadja a paraméterként kapott üzenetet
     *  
     *  @param  string  $key    a betöltött üzenetek tömbben létező kulcs, vagy bármilyen üzenet
     *  @return string
     */
    public static function show($key)
    {
        return (isset(self::$msg_all[$key])) ? self::$msg_all[$key] : $key;
    }
	
	/**
	 *	Üenetek naplózása file-ba
	 *	
	 *	@param	string	$key	a betöltött üzenetek tömbben létező kulcs, vagy bármilyen üzenet
	 *	@param	string	$type	a log file típusa (A típusokat a configban kell beállítani!)
	 *  	(opcionális: ha nincs megadva a $type, akkor a configban megadott 'error' file-ba fog írni,
	 *		ha nics beállítva a configban semmi, akkor a default - logs_error.log fájlba ír.)
	 */
	public static function log($key, $type = 'error')
	{
        $filename = Config::get('log.' . $type, 'logs_error.log');
		$message = date("Y-m-d H:i:s") . ' - ';
		$message .= (isset(self::$msg_all[$key])) ? self::$msg_all[$key] . "\n" : $key . "\n";
		file_put_contents($filename, $message, FILE_APPEND | LOCK_EX);
	}
} // Message class vége
?>