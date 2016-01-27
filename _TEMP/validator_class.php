<?php 
/**
* Validator osztály
*
* 	1. szabály megadása:
*		1. param - egy vizsgálandó mező neve
*		2. param - a label
*		3. param - a szabályok tömbje
*
*		$validator->add_rule('username', 'Felhasználónév', array(
*			'required' => true,
*			'max' => 8,
*			'min' => 2, 		
*			'email' => true, 		
*			'match' => 'password', 		
*		));
*	
*	A match szabálynál egy másik mező nevét kell megadni, amivel egyeznie kell. 
*
*	2. Egyedi üzenet megadása (opcionális)
*		1. param - egy szabály neve, amihez az üzenetet rendelni akarjuk
*		2. param - üzenet, benne egy :label karakterlánc, ide kerül majd a rendes label szöveg
*		
*		$validator->set_message('required', 'A :label mező nem lehet üres!');
*/
class Validate {
	
	/**
     * A szabályokat tartalmazó tömb
	 */	
	private $rules_arr = array();
	
	/**
     * A mezőnevekhez tartozó label-eket tartalmazó tömb (szabály megadásakor meg kell adni egy labelt is)
	 */		
	private $label_arr = array();
	
	/**
     * A szabályokhoz tartazó egyedi üzeneteket tartalmazó tömb
	 */	
	private $rule_msg = array();
		
	/**
	 * A sikeres, vagy sikertelen validálás jelzője (true vagy false)
	 */
	private $_passed = false;

	/**
	 * Hibaüzeneteket tárolja
	 */
	private $_error = array();


	public function __construct(){}

	/**
	 * Validálási szabály hozzárendelése egy mezőhöz
	 *
	 * @param string $field_name    A mező neve, amihez hozzárendeljük a validálási szabályt. 
	 * @param string $label   		Az üzenetekhez meg kell adni egy labelt, ez fogja helyettesíteni a mező nevét az üzenetben.
	 * @param array  $rules   		Szabályokat tartalmazó tömb 
	 */
	public function add_rule($field_name, $label, $rules)
	{
		$this->rules_arr[$field_name] = $rules;
		$this->label_arr[$field_name] = $label;
	}
	
	/**
	 * Egyedi üzenet rendelése egy szabályhoz
	 *
	 * Validate::set_message('required', 'A :label mező nem lehet üres!');	
	 * (ha nincs :label az üzenetben, akkor nem kerül bele az aktuális label neve )
	 *
	 * @param string $rule  - egy szabály neve
	 * @param string $message  - üzenet (tartalamznia kell egy :label elemet amit kicserélünk az aktuális label névre)
	 */
	public function set_message($rule, $message)
	{
		$this->rule_msg[$rule] = $message;
	}
	
	/**
	 * Kicseréli a :label helyörzőt az üzenetben, az add_rule metódus 2. paraméterében megadott label-re
	 *
	 * @param string $message  		az üzenet, amiben ki kell cserélni a :label-t
	 * @param string $field_name  	az "aktuális" mező neve
	 */
	private function _replace_label($message, $field_name)
	{
		return preg_replace('~:label~', $this->label_arr[$field_name], $message);
	}
	
	/**
	 * Validátor metódus
	 *
	 * @param array $source   a vizsgálandó elemeket tartalamzó tömb, például a $_POST tömb
	 */
	public function check($source)
	{
		foreach ($this->rules_arr as $item => $rules) {
			foreach ($rules as $rule => $rule_value) {

				$value = trim($source[$item]);

				// szabály vizsgálatok
				if ($rule === 'required' && empty($value))
				{
					if(isset($this->rule_msg['required'])){
						$this->_error[] = $this->_replace_label($this->rule_msg['required'], $item);
					} else {
						$this->_error[] = $this->label_arr[$item] . '_field_empty';
					}				
				}
				else if (!empty($value))
				{
					switch ($rule) {
						case 'min':
							if (strlen($value) < $rule_value)
							{
								if(isset($this->rule_msg['min'])){
									$this->_error[] = $this->_replace_label($this->rule_msg['min'], $item);
								} else {
									$this->_error[] = $this->label_arr[$item] . '_tul keves karakter';
								}
							}
							break;
						case 'max':
							if (strlen($value) > $rule_value)
							{
								if(isset($this->rule_msg['max'])){
									$this->_error[] = $this->_replace_label($this->rule_msg['min'], $item);
								} else {								
									$this->_error[] = $this->label_arr[$item] . '_tul sok karakter';
								}
							}
							break;
						case 'email':
							if (!filter_var($value, FILTER_VALIDATE_EMAIL))
							{
								if(isset($this->rule_msg['email'])){
									$this->_error[] = $this->_replace_label($this->rule_msg['email'], $item);
								} else {
									$this->_error[] = $this->label_arr[$item] . '_does_not_fit_pattern';
								}								
							}
							break;
						case 'matches':
							if ($value != $source[$rule_value])
							{
								if(isset($this->rule_msg['matches'])){
									$this->_error[] = $this->_replace_label($this->rule_msg['matches'], $item);
								} else {
									$this->_error[] = $this->label_arr[$item] . '_wrong';
								}
							}	
							break;
					}
				}

			}

		}

		if (empty($this->_error)) {
			$this->_passed = true;
		}
	}

	/**
	 * Megvizsgálja, hogy sikeres volt-e a validálás
	 * 
	 * @return boolean
	 */
	public function passed()
	{
		return $this->_passed;
	}

	/**
	 * Visszadja a hibaüzeneteket tartalmazó tömböt
	 *
	 * @return array
	 */
	public function get_error()
	{
		return $this->_error;
	}
}
?>