<?php
namespace System\Libs;

class Filter {

	/**
	 * Veszélyes html tag-eket tartalmazó tömb
	 */
	private $danger_tags = array('script');

	public function __construct(){ }

	/**
	 * Elindítja a szűrő metódust
	 * Többfajta szűrés is végrehajtható, akkor a filter megadása: trim|strip_tags|upper
	 *
	 * @param mixed $data
	 * @param string $filter
	 * @return mixed
	 */
	public function sanitize($data, $filter = null)
	{
		if (is_array($data)) {	
			foreach ($data as $key => $sub_value) {
				$data[$key] = $this->sanitize($sub_value, $filter);
			}
			return $data;
		}

		return $this->_sanitize($data, $filter);
	}


	/**
	 * A tényleges szűrést hajtja végre
	 *
	 */
	private function _sanitize($data, $filter = null)
	{
		/*
		if($data === ''){
			return $data;
		}
		*/

		if(is_null($filter)){
			$filter = 'default';
		}

		$filter_arr = explode('|', $filter);

/*			
		foreach($filter_arr as $filter_name) {
			$function_name = '_filter_' . $filter_name;
			$data = $this->$function_name($data);
		}
*/
		foreach($filter_arr as $filter_name) {

			try {
				
				if(get_magic_quotes_gpc()){
					$data = stripslashes($data);	
				}

				switch ($filter_name) {
					case 'default':
						$data = $this->_filter_trim($data);
						$data = $this->_filter_strip_tags($data);
						break;

					case 'trim':
						$data = $this->_filter_trim($data);
						break;

					case 'strip_danger_tags':
						$data = $this->_filter_strip_danger_tags($data);
						break;

					case 'strip_tags':
						$data = $this->_filter_strip_tags($data);
						break;

					case 'integer':
						$data = $this->_filter_integer($data);
						break;

					case 'float':
						$data = $this->_filter_float($data);
						break;
						
					case 'alphanum':
						$data = $this->_filter_alphanum($data);
						break;

					case 'email':
						$data = $this->_filter_email($data);
						break;

					case 'boolean':
						$data = $this->_filter_boolean($data);
						break;

					case 'lower':
						$data = $this->_filter_lower($data);
						break;

					case 'upper':
						$data = $this->_filter_upper($data);
						break;
					
					default:
						throw new Exception('Nincs ' . $filter_name . ' szuro a Filter objektumban');
						exit();
						break;
				}
			
			}
			catch (Exception $e) {
				die('<br /><strong>Hiba:</strong> ' . $e->getMessage() . '<br />');
			}

		}

		return $data;	
	}



	private function _filter_string()
	{
		
	}

	private function _filter_alphanum($data)
	{
		$data = preg_replace('~[^a-zA-Z0-9áéíóöőüűúÁÉÍÓÖŐÜŰÚ\s]~', '', $data);
		return trim($data);
	}

	private function _filter_integer($data)
	{
		$data = filter_var($data, FILTER_SANITIZE_NUMBER_INT);
		if($data === ''){
			return null;
		}
		return (int)$data;
	}

	private function _filter_float($data)
	{
		$data = filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, array('flags' => FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND));
		if($data === ''){
			return null;
		}
		return (float)str_replace(',', '.', $data);
	}

	private function _filter_boolean($data)
	{
		return filter_var($data, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
	}

	private function _filter_strip_tags($data)
	{
		return strip_tags($data);
	}

	private function _filter_strip_danger_tags($data)
	{
		foreach ($this->danger_tags as $tag) {
			$data = preg_replace('~<' . $tag . '(.*?)>(.*?)</?' . $tag . '>~i', '', $data);
			// $data = preg_replace('~</?' . $tag . '(.*?)>~i', '', $data);
	    }
	    return $data;
	}

	private function _filter_email($data)
	{
		return filter_var($data, FILTER_SANITIZE_EMAIL);
	}

	private function _filter_trim($data)
	{
		return trim($data);
	}

	private function _filter_lower($data)
	{
		return mb_strtolower($data);
	}

	private function _filter_upper($data)
	{
		return mb_strtoupper($data);
	}

}
?>