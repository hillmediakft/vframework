<?php
namespace System\Helper;
/**
* Array helper
*/
class Arr
{
	
	/**
	 *	Visszadja egy tömb megadott kulcsának értékét
	 *
	 *	Példa a kulcsok megadására: (a többdimenziós tömb elemeit a . karakterrel elválasztva kell megadni, mintha egy útvonal lenne)
	 *		_get_array_value($array, 'userinput.firstname');
	 *
	 *	@param   array   $array    Ebből a tömbből adjuk vissza az adatot
	 *	@param   mixed   $key      A kulcs, amit keresünk
	 *	@return  mixed
	 */
	public function get($array, $key)
	{
		foreach (explode('.', $key) as $key_part)
		{
			if (!array_key_exists($key_part, $array))
			{
				return false;
			}
			$array = $array[$key_part];
		}

		return $array;
	}

	/**
	 * Tömb kulcsának és értékének megadása "." karakterrel megadva
	 *
	 *	set($array, 'user_data.user_id', $value);
     *
	 * @param   array   $array  tömb amibe az adatot rakjuk
	 * @param   mixed   $key    kulcsok "." karakterrel elválasztva
	 * @param   mixed   $value  érték
	 * @return  void
	 */
	public function set(&$array, $key, $value = null)
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
	 * Tömb átlakítása pl. $_FILES tömb esetén multiple változatról single verzióra
	 * @param array $files_array - $FILES['upload_files']
	 */
	public function multipleToSingle($files_array)
	{
		$files = array();
		foreach ($files_array as $k => $l) {
			foreach ($l as $i => $v) {
				if (!array_key_exists($i, $files))
				$files[$i] = array();
				$files[$i][$k] = $v;
			}
		}
		return $files;
	}

	/**
	 * Több nyelvi verziót tartalmazó tömböt alakítja át úgy,
	 * hogy az egy rekordhoz tartozó, de különböző nyelvi elemek egy tömbbe kerüljenek.
	 * A forrás tömb tömbelemeiben kell lennie olyan kulcs érték párnak, ami azonosítja a nyelvet!
	 * A nyelvi elemeket tartalamzó tömbelemek kulcsa kapni fog egy nyelvi elemet azonosító kiterjesztést (pl.: _hu, _en stb.)
	 *
	 * @param array $source_array	- forrás tömb
	 * @param array $convert_keys 	- azok a kulcsok (ill. mezőnevek), amelyeknél meg kell különböztetni a nyelvi verziót (pl.: title, description)
	 * @param array $id_key 		- a rekord egyedi azonosítójának neve (pl.: id) 
	 * @param array $lang_code_key 	- a nyelvi hovatartozást megadó mező neve (pl.: language_code) 
	 */
	public function convertMultilanguage($source_array, $convert_keys, $id_key = 'id', $lang_code_key = 'language_code')
	{
		$temp = array();

		foreach ($source_array as $key1 => $value1) {
			
			// ez alapján lesz berakva a suffix elem (_hu vagy _en)
			$lcode = $value1[$lang_code_key];
			// ez lesz a tömbelem indexe 
			$id = $value1[$id_key];

			foreach ($value1 as $key2 => $value2) {
				// nyelvi kódot tartalamzó elemet kihagyja
				if ($key2 == $lang_code_key) {
					continue;
				}

				if (!in_array($key2, $convert_keys)) {
					$temp[$id][$key2] = $value2;
				} else {
					$temp[$id][$key2 . '_' . $lcode] = $value2;
				}

			}

		}
		// ha csak egy eleme van a tömbnek, akkor a kulcsot 0-ra módosítjuk, hogy mindig 0 legyen 1 elem esetén
		if (count($temp) === 1) {
			$temp[0] = array_shift($temp);
		}

		return $temp;
	}

    /**
     * Tömb eleket csoportosítja a tömbelemekben található megadott kulcs szerint
     */    
    public function groupArrayByField($old_arr, $category)
    {
        $arr = array();
        foreach ($old_arr as $key => $item) {
            if (array_key_exists($category, $item))
                $arr[$item[$category]][$key] = $item;
        }
        return $arr;
    } 

    /*
     * array (size=2)
     * 0 => array (size=1)
     *      'term_id' => string '3' (length=1)
     * 1 => array (size=1)
     *      'term_id' => string '4' (length=1)
     * eredmény. array('3', '4') 
     * @param   array az átalakítandó tömb
     * @return  array átalakított tömb
     */

    public function convertArrayToOneDimensional($array)
    {
        $newArray = array();
        if(!empty($array)) {
            foreach ($array as $subArray) {
                foreach ($subArray as $val) {
                    $newArray[] = $val;
                }
            }
        }
        return $newArray;
    }

}
?>