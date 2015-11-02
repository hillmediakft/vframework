<?php 
class Site_model extends Model {

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Oldal szintű beállítások lekérdezése a settings táblából
	 *
	 * @return array a beállítások tömbje
	 */
	public function get_settings()
	{
		$this->query->reset();
		$this->query->set_table(array('settings')); 
		$this->query->set_columns('*'); 
		$result = $this->query->select(); 
		return $result[0];
	}	
	
	/**
	 *	Oldal tartalmak lekérdezése
	 *
	 *	@param	integer	$id 	(page_id az oldal id-je a pages táblában)
	 *	@return array
	 */
	public function page_data_query($id)
	{
		$this->query->reset();		
		$this->query->set_table(array('pages'));		
		$this->query->set_columns('*');
		$this->query->set_where('page_id', '=', $id);
		$result = $this->query->select();
		return $result[0];
	}	
    
    /**
     * E-mail küldés
	 *
     * @param string 	$from_name 				küldő neve
     * @param string	$from_email 			küldő email cim
     * @param string 	$message 				üzenet
     * @param string 	$to_email              	címzett email
     * @param string 	$to_name              	címzett neve
     * @param string 	$subject              	levél tárgya
     *
     * @return boolean
     */
    public function send_email($from_email, $from_name, $subject, $message, $to_email, $to_name)
    {
		// Email kezelő osztály behívása
		//include(LIBS . '/simplemail_class.php');
		
        // Létrehozzuk a SimpleMail objektumot
		$mail = new SimpleMail();
		$mail->setTo($to_email, $to_name)
			 ->setSubject($subject)
			 ->setFrom($from_email, $from_name)
			 ->addMailHeader('Reply-To', $from_email, $from_name)
			 ->addGenericHeader('MIME-Version', '1.0')
			 ->addGenericHeader('Content-Type', 'text/html; charset="utf-8"')
			 ->addGenericHeader('X-Mailer', 'PHP/' . phpversion())
			 ->setMessage($message)
			 ->setWrap(78);
  
        // final sending and check
        if($mail->send()) {
            return true;
        } else {
            return false;
        }
    }    
    
}
?>