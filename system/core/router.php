<?php
/**
 * Router
 *
 * Deconstructs URLs into meaningful paths according
 * to the rules defined in /config/routes.php.
 *
 * Uses Router::find() to parse URL. Returns an array of
 * arrays:
 *   Array (
 *     Array ( controller, action ),
 *     Array ( [param, [param ...]] )
 *   )
 * Where the "params" are defined in the routes.php file.
 */

class Router
{
	private $shorthand = array(
		':controller' => '([a-zA-Z_\-][a-zA-Z0-9_\-]+)',
		':action'     => '([a-zA-Z_\-][a-zA-Z0-9_\-]+)',
		':any'     => '([\w]+)',
		':id'         => '([\d]+)',
		':num'         => '([\d]+)',
		':year'     => '([12][0-9]{3})',
		':month'     => '(0[1-9]|1[012])',
		':day'     => '(0[1-9]|[12][0-9]|3[01])',
		':title'     => '([a-zA-Z_\-][a-zA-Z0-9_\-]+)',
		':hash'     => '(.+)',
	);

	/**
	 *	A controllert tatalmazza
	 */
	public $controller;
	
	/**
	 *	Az action-t tartalmazza
	 */
	public $action;
	
	/**
	 *	Array
	 *	A paramétereket tartalmazó tömb
	 */
	public $params = array();

//-------------------------------------------------------------------------	
	
	public function __construct() {	}

	
	/**
	 * Beállítja a controller, action és a params tulajdonságok értékét
	 */
	public function find($uri_path, $area)
	{
		// behívjuk a $routes tömböt tartalmazó file-t
		if ($area == 'admin') {
			require(CORE . '/routes_admin.php');
		} else {
			require(CORE . '/routes.php');
		}

		// a $key_url a kulcs, a $_route az érték a $routes tömbben. pl: $key_url: ':controller/:action/:id/?', $_route: array('$1/$2', 'id')
		foreach ( $routes as $key_url => $_route ) {
		
		// kiveszi a $_route tömb (pl.: array('$1/$2', 'id')) első elemét, és annak értékét adja vissza (pl.: $1/$2) a $_map változóba (a controller és action stringje)
			$_map = array_shift($_route);

////////////////// ha egy minta sem egyezik az URI-val ///////////////////////	

			// ha nincs egyezés az URI és minták között, akkor elér a ciklus az utolsó mintához ('_error'), és ezt használja a controller és action megállapításához  			
			if ( $key_url == '_error' ) {
				$this->map = explode('/', $_map);
				return;
			}

////////////////// a minta és az URI összehasonlítása ///////////////////////	
				
			// Kicseréli a jelöléseket (pl.: :action) reguláris kifejezésekké ($shorthand tömb alapján)
			$key_url = str_replace(array_keys($this->shorthand), array_values($this->shorthand), $key_url);

			
			// összehasonlítja a mintát ($key_url) az URI-val, az eredményt a $matches tömbben adja vissza 	
			// ha egyezik, akkor tovább fut a kód, és a $matches tömbben adja vissza az egyezéseket. 
			if ( !preg_match('@^'.$key_url.'$@', $uri_path, $matches) ) {
				// ha nincs egyezés, akkor a foreach ciklus a következő elemmel folytatódik	
				continue;
			}

			//Eltávolítja a $matches tömb első elemét (a teljes egyezés pl. 'home/rolunk/param/2'), maradnak a résszminták: pl. home, rolunk, param, 2
			array_shift($matches);
			
			// átalakítjuk a $_map stringet tömbbé a / jel mentén: $1/$2 
			$_map = explode('/', $_map);

			// a $_map tömb bejárása közben a $1 és $2 helyörzőket kicseréljük a $matces 0. és 1. elemével (miközben töröljük őket a $matches tömbből)
			foreach ( $_map as $key => &$_p ) {
				if ( preg_match('#\$(\d+)#', $_p, $_v) ) {
					$_p = str_replace('$'.$_v[1], $matches[$_v[1]-1], $_p);
					unset($matches[$_v[1]-1]);
				} else {
					//ha nem helyörzősen ($1/$2) van megadva az útvonal (akkor is kell venni a $mathes tömbből a controllert és az actiont)
					unset($matches[$key]);
				}
			}
				
			// ha nincs a $_map tömbnek 1. eleme (vagyis nincs action), akkor legyen az action: index
			if (!isset($_map[1])) {
				$_map[1] = 'index';
			}
			
			// kicseréljük a controllerben és az action-ban a - karaktert _ karakterre
			$_map[0] = str_replace("-","_", $_map[0]);
			$_map[1] = str_replace("-","_", $_map[1]);

			// megadjuk a $controller és az $action tulajdonság értékét
			$this->controller = $_map[0];
			$this->action = $_map[1];
				
			// A $matches tömb elejéről kikerült a controller és az action, azért a kulcs számozása 2-től kezdődik. Az array_value() kiveszi az értékeket és ezekből képez tömböt, ami már 0 kulccsal kezdődik
			$params_temp = array_values($matches);
			
			// megadjuk a $params tulajdonság értékét
			$this->params = $this->params_to_assoc($_route, $params_temp);

		return;
		}
	}

	/**
	 * Az URL paramétereket tartalmazó tömböt asszociatív tömbbé alakítja, a $_route tömbben 
	 * lévő paraméter nevekkel. H a $_routes tömb üres, akkor a paraméterekből kulcs -> érték
	 * párokat képez. Ha csak eg yparaméter van, azt id -> érték formában adja vissza
	 *
	 * @param 	array 	$params_temp paraméterek számmal indexelt tömbben
	 * @param 	array	$_route a paraméterek neveit tartalmazó tömb (lehet üres tömb is))
	 */
	public function params_to_assoc($_route, $params_temp) 
	{
			$_params = array();
			
			// paraméterek száma
			$no_of_params = count($params_temp);
			
			// paraméter nevek száma
			$_maxk = count($_route);
			
			/* ha a tömb csak egy elemből áll (pl. : 0 => 12), akkor a következő formátumú tömb elemet hozzuk létre: 'id' => '12'. Amennyiben több elem van a $matches tömbben, akkor létrehozunk egy tömböt, amelyben kulcs => érték formában rendezzük a paramétereket
	
			array 
				  'param1' => '2'
				  'param2' => '77' 
			*/			
			// a $_route tömbben megadtuk a paraméter neveket pl.: array('$1/$2', 'id', 'title')
			// ha nem üres a $_route tömb (vagyis vannak paraméter nevek):

			// ha vannak paraméter nevek megadva
			if (!empty($_route)) {
			
				for ( $j = 0; $j < $_maxk; $j++ ) {
					$_params[$_route[$j]] =  $params_temp[$j];
				}	

			}
			// ha nincsenek paraméter nevek megadva. 
			else {
				if ($no_of_params > 1) {
					for ( $j = 0; $j < $no_of_params; $j+=2 ) {
						$_params[$params_temp[$j]] =  $params_temp[$j+1];
					}
				}
				elseif ($no_of_params == 1){
					$_params['id'] = $params_temp[0];
				}
			}		
		
		return $_params;
	}	
}
?>