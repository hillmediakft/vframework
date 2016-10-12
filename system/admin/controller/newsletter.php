<?php
namespace System\Admin\Controller;
use System\Core\Admin_controller;
use System\Core\View;
use System\Libs\Message;
use System\Libs\Config;

class Newsletter extends Admin_controller {

	/**
	 * Éles elküldést megakadályozza, csak egy teszt fut le ha az értéke true
	 */
	private $debug = true;


	function __construct()
	{
		parent::__construct();
		$this->loadModel('newsletter_model');
		//$this->loadModel('newsletterstat_model');
	}

		private function setDebug(bool $debug)
		{
			$this->debug = $debug;
		}

	public function index()
	{
		$view = new View();

		$view->title = 'Hírlevél oldal';
		$view->description = 'Hírlevél oldal description';

		$view->add_links(array('datatable', 'bootbox', 'vframework', 'newsletter_eventsource'));

		$view->newsletters = $this->newsletter_model->selectNewsletter();	
//$this->view->debug(true);	
		$view->set_layout('tpl_layout');
		$view->render('newsletter/tpl_newsletter');	
	}
	
	/**
	 * Hírlevél hozzáadása
	 */
	public function insert()
	{
		if($this->request->has_post()) {
			
			$data['newsletter_name'] = $this->request->get_post('newsletter_name');
			$data['newsletter_subject'] = $this->request->get_post('newsletter_subject');
			$data['newsletter_body'] = $this->request->get_post('newsletter_body');
			$data['newsletter_status'] = $this->request->get_post('newsletter_status', 'integer');
			$data['newsletter_create_date'] = date('Y-m-d-G:i');

			$result = $this->newsletter_model->insert($data);
			
			if($result !== false) {
				Message::set('success', 'Új hírlevél hozzáadva!');
			} else {
				Message::set('error', 'unknown_error');
			}
			$this->response->redirect('admin/newsletter');
		}

		$view = new View();

		$view->title = 'Hírlevél hozzáadása';
		$view->description = 'Hírlevél oldal description';
		
		$view->add_links(array('bootbox', 'ckeditor', 'vframework', 'newsletter_insert'));
		
		$view->set_layout('tpl_layout');
		$view->render('newsletter/tpl_newsletter_insert');	
	}
	
	/**
	 * Hírlevél módosítása
	 */
	public function update()
	{
		if($this->request->has_post()) {
	
			$data['newsletter_name'] = $this->request->get_post('newsletter_name');
			$data['newsletter_subject'] = $this->request->get_post('newsletter_subject');
			$data['newsletter_body'] = $this->request->get_post('newsletter_body');
			$data['newsletter_status'] = $this->request->get_post('newsletter_status', 'integer');
			$data['newsletter_create_date'] = date('Y-m-d-G:i');

			$result = $this->newsletter_model->update((int)$this->request->get_params('id'), $data);
			
			if($result !== false) {
				Message::set('success', 'Hírlevél módosítva!');
			}
			else {
				Message::set('error', 'unknown_error');
			}
			$this->response->redirect('admin/newsletter');
		}

		$view = new View();

		$view->title = 'Hírlevél szerkesztése';
		$view->description = 'Hírlevél oldal description';
		
		$view->add_links(array('bootbox', 'ckeditor', 'vframework', 'newsletter_update'));
		
		$view->newsletter = $this->newsletter_model->selectNewsletter($this->request->get_params('id'));
	
		$view->set_layout('tpl_layout');
		$view->render('newsletter/tpl_newsletter_update');	
	}

	/**
	 *	Hírlevél törlése AJAX-al
	 */
	public function delete_newsletter_AJAX()
	{
        if($this->request->is_ajax()){
	        if(1){
	        	// a POST-ban kapott user_id egy string ami egy szám vagy számok felsorolása pl.: "23" vagy "12,45,76" 
	        	$id_string = $this->request->get_post('item_id');
				// a sikeres törlések számát tárolja
				$success_counter = 0;
		        // a sikeresen törölt id-ket tartalmazó tömb
		        $success_id = array();		
				// a sikertelen törlések számát tárolja
				$fail_counter = 0; 
		        // a paraméterként kapott stringből tömböt csinálunk a , karakter mentén
		        $id_arr = explode(',', $id_string);
				// bejárjuk a $id_arr tömböt és minden elemen végrehajtjuk a törlést
				foreach($id_arr as $id) {
					//átalakítjuk a integer-ré a kapott adatot
					$id = (int)$id;
					//rekord törlése	
					$result = $this->newsletter_model->delete($id);
					
					if($result !== false) {
						// ha a törlési sql parancsban nincs hiba
						if($result > 0){
							//sikeres törlés
							$success_counter += $result;
							$success_id[] = $id;
						}
						else {
							//sikertelen törlés
							$fail_counter++;
						}
					}
					else {
						// ha a törlési sql parancsban hiba van
		                $this->response->json(array(
		                    'status' => 'error',
		                    'message_error' => 'Hibas sql parancs: nem sikerult a DELETE lekerdezes az adatbazisbol!',                  
		                ));
					}
				}

		        // üzenetek visszaadása
		        $respond = array();
		        $respond['status'] = 'success';
		        
		        if ($success_counter > 0) {
		            $respond['message_success'] = $success_counter . ' hírlevél törölve.';
		        }
		        if ($fail_counter > 0) {
		            $respond['message_error'] = $fail_counter . ' hírlevelet már töröltek!';
		        }
		        // respond tömb visszaadása
				$this->response->json($respond);

	        } else {
	            $this->response->json(array(
	            	'status' => 'error',
	            	'message' => 'Nincs engedélye a művelet végrehajtásához!'
	            ));
	        }
        }
	}

	/**
	 * Hírlevél küldése
	 */
	public function send_newsletter()
	{
		header('Content-Type: text/event-stream');
		// recommended to prevent caching of event data.
		header('Cache-Control: no-cache');
			
		set_time_limit(0); 
		//ob_implicit_flush(true);

		// NewsletterStat_model betöltése
		$this->loadModel('newsletterstat_model');

		$newsletter_id = $this->request->get_query('newsletter_id');


//----------------


		if (!isset($newsletter_id)) {
			$this->send_msg('CLOSE', 'Hibas newsletter_id!');
			exit;
		} else {
			$newsletter_id = (int)$newsletter_id;
		}

		// TESZT futtatása (nincs küldés!)
		if($this->debug){
			$success = 0;
			$fail = 0;
			$max = 7;
			
			for($i = 1; $i <= $max; $i++){
				$number = rand(1000,11000);
				$progress = round(($i/$max)*100); //Progress
				//Hard work!!
				sleep(1);
				if($number > 4000){
					$success += 1;
					$this->send_msg($i, 'Sikeres   | id:' . $newsletter_id .  '|   küldés a ' . $number . '@mail.hu címre', $progress);				
					
				} else{
					$fail += 1;
					$this->send_msg($i, 'Sikertelen   | id: ' . $newsletter_id .  '|   küldés a ' . $number . '@mail.hu címre', $progress);				
				}
			}

			sleep(1);

				// adatok beírása a stats_newsletters táblába
				$data['sent_date'] = date('Y-m-d-G:i');
				$data['newsletter_id'] = $newsletter_id;
				$data['recepients'] = $success + $fail;
				$data['send_success'] = $success;
				$data['send_fail'] = $fail;
				$this->newsletterstat_model->insertStat($data);

			//utolsó válasz
			$this->send_msg('CLOSE', '<br />Sikeres küldések száma: ' . $success . '<br />' . 'Sikertelen küldések száma: ' . $fail. '<br />');
			exit;
		}
		// éles küldés!!			
		else {
			$error = array();
			$success = array();

			$data['newsletter_id'] = $newsletter_id;
			$data['sent_date'] = date('Y-m-d-G:i');
			$data['error'] = 1;
			// új rekord a stat_newsletter táblába (visszatér a last_insert_id-vel)	
			$statid = $this->newsletterstat_model->insertStat($data);

			// statid lekérdezése		
			//$statid = $this->newsletterstat_model->selectStatId();

			// elküldendő hírlevél eleminek lekérdezése	
			$newsletter_temp = $this->newsletter_model->selectNewsletter($newsletter_id);
			// e-mail címek, és hozzájuk tartozó user nevek (akiknek küldeni kell)
			$users_data = $this->newsletter_model->userEmails();
			
			foreach($newsletter_temp as $value) {
				$subject = $value['newsletter_subject'];
				$body = $value['newsletter_body'];
			} 

			foreach($users_data as $value) {
				$user_emails[] = $value['user_email'];
				$user_names[] = $value['user_name'];
				
				$user_ids[] = $value['user_id'];
                $user_unsubs[] = $value['user_unsubscribe_code'];
			} 
			
			//az összes email_cím száma
			$all_email_address = count($user_emails);
			

/*----- Email-ek küldése -------*/
			
			
			// küldés simple mail-el történjen
			$simple_mail = false;
			
			// küldés simple mail-el
			if($simple_mail === true) {
				// Létrehozzuk a SimpleMail objektumot
				$mail = new \System\Libs\SimpleMail();
				
				//a ciklusok számát fogja számolni (vagyis hogy éppen mennyi emailt küldött el)	
				$progress_counter = 0; 				
				
				foreach($user_emails as $key => $mail_address) {

/*
		//Since the tracking URL is a bit long, I usually put it in a variable of it's own
		$tracker = URL . 'track_open/' . $user_ids[$key] . '/' . $statid;

		//Add the tracker to the message.
		$message = '<img alt="" src="'.$tracker.'" width="1" height="1" border="0" />';
		$unsubscribe_url = URL . 'leiratkozas/' . $user_ids[$key] . '/' . $user_unsubs[$key];
		$unsubscribe = '<p>Leiratkozáshoz kattintson a következő linkre: <a href="' . $unsubscribe_url . '">Leiratkozás</a></p>';
*/

					$progress_counter += 1;  
					//küldés állapota %-ban
					$progress = round(($progress_counter / $all_email_address) * 100);

					$mail->setTo($mail_address, $user_names[$key])
						 ->setSubject($subject)
						 ->setFrom('valami@teszt.hu', 'anonymous')
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
						$this->send_msg($progress_counter, 'Sikertelen küldés a ' . $mail_address . ' címre', $progress);				
					}
					
					$mail->reset();
				} 
			}
			else {

				// küldés PHPMailer-el
				$mail = new \PHPMailer();
				$settings = Config::get('email.server');

				if(true){
				//SMTP beállítások!!
					$mail->isSMTP(); // Set mailer to use SMTP				
					//$mail->SMTPDebug = PHPMAILER_DEBUG_MODE; // Enable verbose debug output
					$mail->SMTPAuth = $settings['smtp_auth']; // Enable SMTP authentication
					//$mail->SMTPKeepAlive = true; // SMTP connection will not close after each email sent, reduces SMTP overhead
					
					// Specify SMTP host server
					$mail->Host = $settings['smtp_host'];
					//$mail->Host = 'localhost';
					$mail->Username = $settings['smtp_username']; // SMTP username
					$mail->Password = $settings['smtp_password']; // SMTP password
					$mail->Port = $settings['smtp_port']; // TCP port to connect to
					$mail->SMTPSecure = $settings['smtp_encryption']; // Enable TLS encryption, `ssl` also accepted
				} else {
					$mail->IsMail();
				}
				
				$mail->CharSet = 'UTF-8'; //karakterkódolás beállítása
				$mail->WordWrap = 78; //sortörés beállítása (a default 0 - vagyis nincs)
				$mail->From = $settings['from_email']; //feladó e-mail címe
				$mail->FromName = $settings['from_name']; //feladó neve
				$mail->addReplyTo('info@example.com', 'Information'); //Set an alternative reply-to address
				$mail->Subject = $subject; // Tárgy megadása
				$mail->isHTML(true); // Set email format to HTML                                  

				$mail->Body = '<html><body>' . $body . '</body></html>';

				//a ciklusok számát fogja számolni (vagyis hogy éppen mennyi emailt küldött el)	
				$progress_counter = 0;  

				//email-ek elküldés ciklussal
				foreach($user_emails as $key => $mail_address)
				{
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
						//folyamat alatti válaszüzenet küldése
						$this->send_msg($progress_counter, 'Sikeres küldés a ' . $mail_address . ' címre', $progress);				
					} else {
						$error[] = $mail_address;
						//folyamat alatti válaszüzenet küldése
						$this->send_msg($progress_counter, 'Sikertelen küldés a ' . $mail_address . ' címre', $progress);				
					}	
					
					$mail->clearAddresses();
					$mail->clearAttachments();
				}
			}	

// ----- email küldés vége -------------			

			// ha volt sikeres küldés, adatbázisba írjuk az elküldés dátumát
			if(count($success) > 0){
				// az adatbázisban módosítjuk az utolsó küldés mező tartalmát
				$lastsent_date = date('Y-m-d-G:i');
				$this->newsletter_model->updateLastSentDate($newsletter_id, $lastsent_date);
			}
			// adatok beírása a stats_newsletters táblába
			$data['recepients'] = count($success) + count($error);
			$data['send_success'] = count($success);
			$data['send_fail'] = count($error);
			$data['error'] = 0;
			
			// adatok módosítása a newsletter_stats táblában
			$this->newsletterstat_model->updateStat($newsletter_id, $data);
		
		// utolsó válasz		
		$this->send_msg('CLOSE', '<br />Sikeres küldések száma: ' . $data['send_success'] . '<br />' . 'Sikertelen küldések száma: ' . $data['send_fail'] . '<br />');
		
		} // email küldés vége
		
	}
	
		/**
		 * Hírlevél küldése közben üzenetet küld a böngészőnek a folyamat állásáról
		 */
		private function send_msg($id, $message, $progress = null)
		{
			$d = array('message' => $message , 'progress' => $progress);
			  
			echo "id: $id" . PHP_EOL;
			echo "data: " . json_encode($d) . PHP_EOL;
			echo PHP_EOL;
			  
			//ob_flush();
			flush();
		}


	/**
	 * Elküldött hírlevelek
	 */
	public function newsletter_stats()
	{
		// NewsletterStat_model betöltése
		$this->loadModel('newsletterstat_model');
		
		$view = new View();
		
		$view->title = 'Elküldött hírlevelek oldal';
		$view->description = 'Elküldött hírlevél oldal description';

		$view->add_links(array('datatable', 'bootbox', 'vframework', 'newsletter_stats'));
		
		$view->newsletters = $this->newsletterstat_model->selectStats();	
//$this->view->debug(true);	
		$view->set_layout('tpl_layout');
		$view->render('newsletter/tpl_newsletter_stats');	
	}	
	
}	
?>