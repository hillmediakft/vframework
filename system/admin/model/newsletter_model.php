<?php 
class Newsletter_model extends Admin_model {

	/**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
	function __construct()
	{
		parent::__construct();
	}

	
	/*
	public function all_user()
	{
		// a query tulajdonság ($this->query) tartalmazza a query objektumot
		$this->query->set_table(array('users')); 
		$this->query->set_columns(array('users.user_id', 'users.user_name', 'users.user_email', 'users.user_active', 'users.user_role_id', 'users.user_first_name', 'users.user_last_name', 'users.user_phone', 'users.user_photo', 'roles.role_name')); 
		$this->query->set_join('left', 'roles', 'users.user_role_id', '=', 'roles.role_id'); 
		$result = $this->query->select(); 
	
		return $result;
	}
	*/

	/**
	 *	Visszaadja a newsletter tábla tartalmát
	 *	Ha kap egy id paramétert (integer), akkor csak egy sort ad vissza a táblából
	 *
	 *	@param $id Integer 
	 */
	public function newsletter_query($id = null)
	{
		$this->query->reset(); 
		$this->query->set_table(array('newsletters')); 
		$this->query->set_columns('*'); 
		if(!is_null($id)){
			$id = (int)$id;
			$this->query->set_where('newsletter_id', '=', $id); 
		}
		return $this->query->select(); 
	}

	
	
	public function user_email_query()
	{
		$this->query->reset(); 
		$this->query->set_table(array('site_users')); 
		$this->query->set_columns(array('user_name', 'user_email')); 
		$this->query->set_where('user_newsletter', '=', 1); 
		return $this->query->select(); 
	}
	
	
	
	public function new_newsletter()
	{
		$data['newsletter_name'] = $_POST['newsletter_name'];
		$data['newsletter_subject'] = $_POST['newsletter_subject'];
		$data['newsletter_body'] = $_POST['newsletter_body'];
		$data['newsletter_status'] = (int)$_POST['newsletter_status'];
		$data['newsletter_create_date'] = date('Y-m-d-G:i');
		
		$this->query->reset();
		$this->query->set_table(array('newsletters'));
		$result = $this->query->insert($data);
	
	// ha sikeres az insert visszatérési érték true
		if($result) {
			$_SESSION["feedback_positive"][] = "Új hírlevél hozzáadva!";
			return true;
		}
		else {
			$_SESSION["feedback_negative"][] = FEEDBACK_UNKNOWN_ERROR;
			return false;
		}
	
	
	}

	
	public function edit_newsletter($id)
	{
		$id = (int)$id;
	
		$data['newsletter_name'] = $_POST['newsletter_name'];
		$data['newsletter_subject'] = $_POST['newsletter_subject'];
		$data['newsletter_body'] = $_POST['newsletter_body'];
		$data['newsletter_status'] = (int)$_POST['newsletter_status'];
		//$data['newsletter_create_date'] = date('Y-m-d-G:i');
		
		$this->query->reset();
		$this->query->set_table(array('newsletters'));
		$this->query->set_where('newsletter_id', '=', $id);
		$result = $this->query->update($data);
	
	// ha sikeres az insert visszatérési érték true
		if($result) {
			$_SESSION["feedback_positive"][] = "Hírlevél módosítva!";
			return true;
		}
		else {
			$_SESSION["feedback_negative"][] = FEEDBACK_UNKNOWN_ERROR;
			return false;
		}
	
	}	
	
	

	public function delete_newsletter()
	{
		// a sikeres törlések számát tárolja
		$success_counter = 0;
		// a sikertelen törlések számát tárolja
		$fail_counter = 0; 
		
		// Több user törlése
		if(isset($_POST['delete_newsletter'])) {
			$data_arr = $_POST;
			
			//eltávolítjuk a tömbből a felesleges elemeket	
			if(isset($data_arr['delete_newsletter'])) {
				unset($data_arr['delete_newsletter']);
			}
			if(isset($data_arr['newsletter_table_length'])) {
				unset($data_arr['newsletter_table_length']);
			}
		} else {
		// egy user törlése (nem POST adatok alapján)
			if(!isset($this->registry->params['id'])){
				throw new Exception('Nincs id-t tartalmazo parameter az url-ben (ezert nem tudunk torolni id alapjan)!');
				return false;
			}
			//berakjuk a $data_arr tömbbe a törlendő felhasználó id-jét
			$data_arr = array($this->registry->params['id']);
		}

		// bejárjuk a $data_arr tömböt és minden elemen végrehajtjuk a törlést
		foreach($data_arr as $value) {
			//átalakítjuk a integer-ré a kapott adatot
			$value = (int)$value;
				
			//felhasználó törlése	
			$this->query->reset();
			$this->query->set_table(array('newsletters'));
			//a delete() metódus integert (lehet 0 is) vagy false-ot ad vissza
			$result = $this->query->delete('newsletter_id', '=', $value);
			
			if($result !== false) {
				// ha a törlési sql parancsban nincs hiba
				if($result > 0){
					//sikeres törlés
					$success_counter += $result;
				}
				else {
					//sikertelen törlés
					$fail_counter += 1;
				}
			}
			else {
				// ha a törlési sql parancsban hiba van
				throw new Exception('Hibas sql parancs: nem sikerult a DELETE lekerdezes az adatbazisbol!');
				return false;
			}
		}

		// üzenetek eltárolása
		if($success_counter > 0) {
			$_SESSION["feedback_positive"][] = $success_counter . ' ' . 'hírlevél törlése sikerült.';
		}
		if($fail_counter > 0){
			$_SESSION["feedback_negative"][] = $fail_counter . ' ' . 'hírlevél törlése nem sikerült!';
		}
		
		// default visszatérési érték (akkor tér vissza false-al ha hibás az sql parancs)	
		return true;	
	}



	/**
	 *	Hírlevél törlése AJAX-al
	 *
	 */
	public function delete_newsletter_AJAX()
	{
		// a sikeres törlések számát tárolja
		$success_counter = 0;
		// a sikertelen törlések számát tárolja
		$fail_counter = 0; 
		
		// Több user törlése
		if(isset($_POST['newsletter_id'])) {
			$data_arr = $_POST;

			//eltávolítjuk a tömbből a felesleges elemeket	
			if(isset($data_arr['delete_newsletter'])) {
				unset($data_arr['delete_newsletter']);
			}
			if(isset($data_arr['newsletter_table_length'])) {
				unset($data_arr['newsletter_table_length']);
			}
		} else {
			return false;
		}

		// bejárjuk a $data_arr tömböt és minden elemen végrehajtjuk a törlést
		foreach($data_arr as $value) {
			//átalakítjuk a integer-ré a kapott adatot
			$value = (int)$value;
				
			//felhasználó törlése	
			$this->query->reset();
			$this->query->set_table(array('newsletters'));
			//a delete() metódus integert (lehet 0 is) vagy false-ot ad vissza
			$result = $this->query->delete('newsletter_id', '=', $value);
			
			if($result !== false) {
				// ha a törlési sql parancsban nincs hiba
				if($result > 0){
					//sikeres törlés
					$success_counter += $result;
				}
				else {
					//sikertelen törlés
					$fail_counter += 1;
				}
			}
			else {
				// ha a törlési sql parancsban hiba van
				throw new Exception('Hibas sql parancs: nem sikerult a DELETE lekerdezes az adatbazisbol!');
				return false;
			}
		}

		// üzenetek eltárolása
		if($success_counter > 0) {
			$response = array(
				"status" => 'success',
				"message" => 'Hírlevél törölve.'
			);
			return json_encode($response);
			
		}
		if($fail_counter > 0){
			$response = array(
				"status" => 'error',
				"message" => 'Hírlevél törlése nem sikerült!'
			);
			return json_encode($response);
		}
		
		// default visszatérési érték (akkor tér vissza false-al ha hibás az sql parancs)	
		return true;	
	}

	
	
	
	
	
	
	
	
	
	public function set_progress($value)
	{
		$this->query->reset();
		$this->query->set_table(array('newsletter_progress'));
		$this->query->set_where('id', '=', 1);
		//$this->query->set_column(array('percent'));
		$this->query->update(array('percent' => $value));		
	}

	public function get_progress()
	{
		$this->query->reset();
		$this->query->set_table(array('newsletter_progress'));
		$this->query->set_columns(array('percent'));
		$this->query->set_where(array('id', '=', 1));
		return $this->query->select();			
	}
	
	
	private function send_msg($id, $message, $progress) {
		
		$d = array('message' => $message , 'progress' => $progress);
		  
		echo "id: $id" . PHP_EOL;
		echo "data: " . json_encode($d) . PHP_EOL;
		echo PHP_EOL;
		  
		//ob_flush();
		flush();
	}	





	
	
	
	/**
	 *	Hírlevelek elküldése
	 *
	 *	$_POST['message_id']
	 *
	 */
	public function send_newsletter()
	{
		$debug = false;
		
		$x = Session::get('newsletter_id');
		$id = (isset($x)) ? $x : 'rossz';
		
		/*
		if(isset($_POST['newsletter_id'])){
			$id = $_POST['newsletter_id'];
		} else {
			$id = 'nincsen!!!';
		}
		*/

		
		if($debug){
			
			$success = 0;
			$fail = 0;
			
			$max = 5;
			
			for($i = 1; $i <= $max; $i++){
				
				$number = rand(1000,11000);
			
				$progress = round(($i/$max)*100); //Progress
			
				//Hard work!!
				sleep(1);

				if($number > 4000){
					$success += 1;
					$this->send_msg($i, 'Sikeres   | id:' . $id .  '|   küldés a ' . $number . '@mail.hu címre', $progress);				
					
				}
				else{
					$fail += 1;
					$this->send_msg($i, 'Sikertelen   | id: ' . $id .  '|   küldés a ' . $number . '@mail.hu címre', $progress);				
				}

			}

			sleep(1);

	
				// adatok beírása a stats_newsletters táblába
				$data['sent_date'] = date('Y-m-d-G:i');
				$data['newsletter_id'] = $newsletter_id = (int)$_POST['newsletter_id'];;
				$data['recepients'] = $success + $fail;
				$data['send_success'] = $success;
				$data['send_fail'] = $fail;
				$this->query->reset();
				$this->query->set_table(array('stats_newsletters'));
				$this->query->insert($data);			

			
			//utolsó válasz
			$this->send_msg('CLOSE', '<br />Sikeres küldések száma: ' . $success . '<br />' . 'Sikertelen küldések száma: ' . $fail. '<br />');


			
		} // debug vége
		else {
			$error = array();
			$success = array();

	// id megadása	
	$x = Session::get('newsletter_id');
	$newsletter_id = (isset($x)) ? $x : null;
	//$newsletter_id = (int)$_POST['newsletter_id'];
			
			
		$data['newsletter_id'] = $newsletter_id;
		$data['sent_date'] = date('Y-m-d-G:i');
		$data['error'] = 1;
		
		$this->query->reset();
		$this->query->set_table(array('stats_newsletters'));
		$result = $this->query->insert($data);
		
		$this->query->reset();
		$this->query->set_table(array('stats_newsletters'));
		$this->query->set_columns('statid');
		$this->query->set_orderby('statid', 'DESC');
		$this->query->set_limit(1);
		$result = $this->query->select();
			
		$statid = (int)$result[0]['statid'];


			// elküldendő hírlevél eleminek lekérdezése	
			$newsletter_temp = $this->newsletter_query((int)$newsletter_id);
			// e-mail címek, és hozzájuk tartozó user nevek (akiknek küldeni kell)
			$email_temp = $this->user_email_query();
			
			foreach($newsletter_temp as $value) {
				$subject = $value['newsletter_subject'];
				$body = $value['newsletter_body'];
			} 

			foreach($email_temp as $value) {
				$user_emails[] = $value['user_email'];
				$user_names[] = $value['user_name'];
				
				$user_ids[] = $value['user_id'];
                $user_unsubs[] = $value['user_unsubscribe_code'];
			} 
			
			//az összes email_cím száma
			$all_email_address = count($user_emails);
			

/*----- Email-ek küldése -------*/
			
			// küldés simple mail-el történjen
			$simple_mail = true;
			
			// küldés simple mail-el
			if($simple_mail === true) {
				// Email kezelő osztály behívása
				include(LIBS . '/simple_mail_class.php');

				// Létrehozzuk a SimpleMail objektumot
				$mail = new SimpleMail();
				
				//a ciklusok számát fogja számolni (vagyis hogy éppen mennyi emailt küldött el)	
				$progress_counter = 0; 				
				
				foreach($user_emails as $key => $mail_address) {

		//Since the tracking URL is a bit long, I usually put it in a variable of it's own
		$tracker = URL . 'track_open/' . $user_ids[$key] . '/' . $statid;

		//Add the tracker to the message.
		$message = '<img alt="" src="'.$tracker.'" width="1" height="1" border="0" />';
		$unsubscribe_url = URL . 'leiratkozas/' . $user_ids[$key] . '/' . $user_unsubs[$key];
		$unsubscribe = '<p>Leiratkozáshoz kattintson a következő linkre: <a href="' . $unsubscribe_url . '">Leiratkozás</a></p>';


					$progress_counter += 1;  
					//küldés állapota %-ban
					$progress = round(($progress_counter / $all_email_address) * 100);

					$mail->setTo($mail_address, $user_names[$key])
						 ->setSubject($subject)
						 ->setFrom(EMAIL_VERIFICATION_FROM_EMAIL, EMAIL_VERIFICATION_FROM_NAME)
						 ->addMailHeader('Reply-To', 'sender@gmail.com', 'Mail Bot')
						 ->addGenericHeader('MIME-Version', '1.0')
						 ->addGenericHeader('Content-Type', 'text/html; charset="utf-8"')
						 ->addGenericHeader('X-Mailer', 'PHP/' . phpversion())
						 ->setMessage('<html><body>' . $body . '</body></html>')
						 ->setWrap(78);
			  
					// final sending and check
					if($mail->send()) {
						$success[] = $mail_address;
		
		//üzenet küldése	
		$this->send_msg($progress_counter, 'Sikeres küldés a ' . $mail_address . ' címre', $progress);				
			

					} else {
						$error[] = $mail_address;
		//üzenet küldése				
		$this->send_msg($progress_counter, 'Sikeres küldés a ' . $mail_address . ' címre', $progress);				

						
					}
					
					$mail->reset();
				} 
			} else {
				// küldés PHPMailer-el
				include(LIBS . '/PHPMailer/PHPMailerAutoload.php');
				
				$mail = new PHPMailer;

				if(EMAIL_USE_SMTP){
				//SMTP beállítások!!
					$mail->isSMTP(); // Set mailer to use SMTP				
					//$mail->SMTPDebug = PHPMAILER_DEBUG_MODE; // Enable verbose debug output
					$mail->SMTPAuth = EMAIL_SMTP_AUTH; // Enable SMTP authentication
					//$mail->SMTPKeepAlive = true; // SMTP connection will not close after each email sent, reduces SMTP overhead
					
					// Specify SMTP host server
					$mail->Host = EMAIL_SMTP_HOST;
					//$mail->Host = 'localhost';
					$mail->Username = EMAIL_SMTP_USERNAME; // SMTP username
					$mail->Password = EMAIL_SMTP_PASSWORD; // SMTP password
					$mail->Port = EMAIL_SMTP_PORT; // TCP port to connect to
					$mail->SMTPSecure = EMAIL_SMTP_ENCRYPTION; // Enable TLS encryption, `ssl` also accepted
				} else {
					$mail->IsMail();
				}
				
				$mail->CharSet = 'UTF-8'; //karakterkódolás beállítása
				$mail->WordWrap = 78; //sortörés beállítása (a default 0 - vagyis nincs)
				$mail->From = EMAIL_FROM_EMAIL; //feladó e-mail címe
				$mail->FromName = EMAIL_FROM_NAME; //feladó neve
				$mail->addReplyTo('info@example.com', 'Information'); //Set an alternative reply-to address
				$mail->Subject = $subject; // Tárgy megadása
				$mail->isHTML(true); // Set email format to HTML                                  

				$mail->Body = '<html><body>' . $body . '</body></html>';

				//a ciklusok számát fogja számolni (vagyis hogy éppen mennyi emailt küldött el)	
				$progress_counter = 0;  

				//email-ek elküldés ciklussal
				foreach($user_emails as $key => $mail_address) {
					
					$progress_counter += 1;  
					//küldés állapota %-ban
					$progress = round(($progress_counter / $all_email_address) * 100);
				
					$mail->addAddress($mail_address, $user_names[$key]);     // Add a recipient (Name is optional)
					//$mail->addCC('cc@example.com');
					//$mail->addBCC('bcc@example.com');

					//$mail->addStringAttachment('image_eleresi_ut_az_adatbazisban', 'YourPhoto.jpg'); //Assumes the image data is stored in the DB
					//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
					//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

					//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';	
			  
					// final sending and check
					if($mail->send()) {
						$success[] = $mail_address;
						
						//folyamat alatti válasz
						$response = array( 
							//"message" => $number . '@mail.hu címre sikeres küldés!', 
							//"success" => 1,
							//"fail" => 0,
							"progress" => $progress
						);						
						echo json_encode($response);	
					
					} else {
						$error[] = $mail_address;
						
						//folyamat alatti válasz
						$response = array( 
							//"message" => $number . '@mail.hu címre sikeres küldés!', 
							//"success" => 0,
							//"fail" => 1,
							"progress" => $progress
						);						
						echo json_encode($response);
					}	
					
					$mail->clearAddresses();
					$mail->clearAttachments();
				}
			
			}
			
			// ha volt sikeres küldés, adatbázisba írjuk az elküldés dátumát
			if(count($success) > 0){
				// az adatbázisban módosítjuk az utolsó küldés mező tartalmát
				$lastsent_date = date('Y-m-d-G:i');
				$this->query->reset();
				$this->query->set_table(array('newsletters'));
				$this->query->set_where('newsletter_id', '=', $newsletter_id);
				$this->query->update(array('newsletter_lastsent_date' => $lastsent_date));
			}
			// adatok beírása a stats_newsletters táblába
			$data['recepients'] = count($success) + count($error);
			$data['send_success'] = count($success);
			$data['send_fail'] = count($error);
			$data['error'] = 0;
			
			$this->query->reset();
			$this->query->set_table(array('stats_newsletters'));
			$this->query->set_where('newsletter_id', '=', $newsletter_id);
			$result = $this->query->update($data);
			
	// utolsó válasz		
	$this->send_msg('CLOSE', '<br />Sikeres küldések száma: ' . count($success) . '<br />' . 'Sikertelen küldések száma: ' . count($fail) . '<br />');
		
		} // email küldés vége
		
	}
	
	/**
	 *	Visszaadja a newsletter_stats tábla tartalmát
	 *	
	 *
	 *	@param 
	 */
	public function newsletter_stats_query()
	{
		$this->query->reset(); 
		$this->query->set_table('stats_newsletters'); 
		$this->query->set_columns(array('statid', 'stats_newsletters.newsletter_id', 'sent_date', 'recepients', 'send_success', 'send_fail', 'email_opens', 'unsubscribe_count', 'error', 'newsletters.newsletter_name', 'newsletters.newsletter_subject' )); 

		$this->query->set_join('left', 'newsletters', 'stats_newsletters.newsletter_id', '=', 'newsletters.newsletter_id');
		$this->query->set_orderby('statid', 'DESC');
		return $this->query->select(); 
	}	
	
}
?>