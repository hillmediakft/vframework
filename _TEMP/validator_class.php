<?php 
/**
* Validator osztály
*/
class Validate {
	
	/**
     * A szabályokat tartalmazó tömb
	 */	
	public $rules_arr = array();
	
	/**
     * A label-eket tartalmazó tömb (szabály megadásakor meg kell adni egy labelt is)
	 */		
	public $label_arr = array();
	
	/**
     * A szabályokhoz tartazó egyedi üzeneteket tartalmazó tömb
	 */	
	public $rule_msg = array(
		/*'required' => null,
		'min' => null,
		'max' => null,
		'email' => null,
		'matches' => null
		*/
	);
	
	
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
	 * @param string $name  A mező neve, amihez hozzárendeljük a validálási szabályt. 
	 * @param string $label  Az üzenetekhez meg kell adni egy labelt, ez fogja helyettesíteni a mező nevét az üzenetben. 
	 */
	public function add_rule($name, $label, $rules)
	{
		$this->rules_arr[$name] = $rules;
		$this->label_arr[$name] = $label;
	}
	
	/**
	 * Egyedi üzenet rendelése egy szabályhoz
	 *
	 * Validate::set_message('required', 'A :label mező nem lehet üres!');	
	 * (ha nincs :label az üzenetben, akkor nem kerül bele az aktuális label neve )
	 * @param string $rule  - egy szabály neve
	 * @param string $message  - üzenet (tartalamznia kell egy :label elemet amit kicserélünk az aktuális label névre)
	 */
	public function set_message($rule, $message)
	{
			$this->rule_msg[$rule] = $message;
		/*
		if(array_key_exists($rule, $this->rule_msg)){
			$this->rule_msg[$rule] = $message;
		} else {
			throw new Exception('Nem letezik - ' . $rule . ' - szabaly, amihez az uzenetet rendeltuk.'); 
		}
		*/
	}
	
	/**
	 * Kicseréli a :label az üzenetben a megfelelő label-re
	 *
	 * @param string $message  - az üzenet, amiben ki kell cserélni a :label-t
	 * @param string $item  - az aktuális mező neve
	 */
	private function replace_label($message, $item)
	{
		return preg_replace('~:label~', $this->label_arr[$item], $message);
	}
	
	
	/**
	 * validátor metódus
	 *
	 * $source tömb - vizsgálandó elemek ($_POST)
	 * $items tömb - szabályok
	 *
	 *  $validator->add_rule('username', 'Felhasznalonev', array(
	 *		'required' => true,
	 *		'max' => 5
	 *	));
	 *	$validator->add_rule('password', 'jelszo', array(
	 *		'required' => true,
	 *		'min' => 5
	 *
	 *	A $source tömb kulcsáak meg kell egyeznie az add_rule metódus első paraméterével
     *
	 * @param array $source (például a $_POST tömb)
	 */
	public function check($source)
	{
		foreach ($this->rules_arr as $item => $rules) {
			foreach ($rules as $rule => $rule_value) {

				$value = trim($source[$item]);

				// szabály vizsgálatok
				if ($rule === 'required' && empty($value)) {
					
					if(isset($this->rule_msg['required'])){
						$this->_error[] = $this->replace_label($this->rule_msg['required'], $item);
					} else {
						$this->_error[] = $this->label_arr[$item] . '_field_empty';
					}				
				
				} else if (!empty($value)){
					switch ($rule) {
						case 'min':
							if (strlen($value) < $rule_value){
								
								if(isset($this->rule_msg['min'])){
									$this->_error[] = $this->replace_label($this->rule_msg['min'], $item);
								} else {
									$this->_error[] = $this->label_arr[$item] . '_tul keves karakter';
								}
						
							}
							break;
						case 'max':
							if (strlen($value) > $rule_value){
								
								if(isset($this->rule_msg['max'])){
									$this->_error[] = $this->replace_label($this->rule_msg['min'], $item);
								} else {								
									$this->_error[] = $this->label_arr[$item] . '_tul sok karakter';
								}
							}
							break;
						case 'email':
							if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
								
								if(isset($this->rule_msg['email'])){
									$this->_error[] = $this->replace_label($this->rule_msg['email'], $item);
								} else {
									$this->_error[] = $this->label_arr[$item] . '_does_not_fit_pattern';
								}								
								
							}
							break;
						case 'matches':
							if ($value != $source[$rule_value]) {
								
								if(isset($this->rule_msg['matches'])){
									$this->_error[] = $this->replace_label($this->rule_msg['matches'], $item);
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
	 * validátor metódus
	 *
	 * $source tömb - vizsgálandó elemek ($_POST)
	 * $items tömb - szabályok
	 * 
	 * SZABÁLYOK TÖMBJE MINTA:
	 *
	 *	$items = array(
	 *		'username' => array(
	 *			'required' => true,
	 *			'max' => 5
	 *		),
	 *		'password' => array(
	 *			'required' => true,
	 *			'min' => 8		
	 *		),
	 *		'password_again' => array(
	 *			'matches' => 'password'	
	 *		),
	 *		'email' => array(
	 *			'email' => true	
	 *		)
	 *	);
	 *
	 *	A $source tömb kulcsainak meg kell egyeznie a szabályok tömb kulcsaival
     *
	 * @param array $source (például a $_POST tömb)
	 * @param array $items (szabályokat tartalmazó tömb)
	 */
	 /*
	public function check_old($source, $items = array())
	{
		foreach ($items as $item => $rules) {
			foreach ($rules as $rule => $rule_value) {

				$value = trim($source[$item]);

				// szabály vizsgálatok
				if ($rule === 'required' && empty($value)) {
					$this->_error[] = 'a mezo nem lehet ures';
				} else if (!empty($value)){
					switch ($rule) {
						case 'min':
							if (strlen($value) < $rule_value){
								$this->_error[] = 'tul keves karakter';
							}
							break;
						case 'max':
							if (strlen($value) > $rule_value){
								$this->_error[] = 'tul sok karakter';
							}
							break;
						case 'email':
							if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
								$this->_error[] = 'nem megfelelo az email cim formatuma';
							}
							break;
						case 'matches':
							if ($value != $source[$rule_value]) {
								$this->_error[] = 'A jelszavak nem egyeznek';
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
	*/

	/**
	 * Megvizsgálja, hogy sikeres volt-e a validálás
	 * TRUE vagy FALSE értéke lehet
	 */
	public function passed()
	{
		return $this->_passed;
	}

	public function get_error()
	{
		return $this->_error;
	}

}
?>