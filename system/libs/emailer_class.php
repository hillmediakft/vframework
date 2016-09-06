<?php

/**
 * 	Emailer class v1.0
 *
 * 	Használat (példa):
 * 		 

 */
class Emailer {

    /**
     * Küldő neve
     */
    private $from_name;

    /**
     * Küldő e-mail címe
     */
    private $from_email;

    /**
     * E-mail template neve
     */
    private $template;

    /**
     * $_POST adatok
     */
    private $form_data;

    /**
     * E-mail tárgya
     */
    private $subject;

    /**
     * Címzett e-mail címe
     */
    private $to_email;

    /**
     * Címzett neve
     */
    private $to_name;

    /**
     * Címzett neve
     */
    private $use_smtp = false;

    /**
     * Email küldés sikeressége (sikeres: true, sikertelen: false)
     */
    public $status;

    /**
     * CONSTRUCTOR
     *
     * @param  string   $pagename 	GET paraméter neve ami a lapozáshoz kapcsolódik
     * @param  integer  $limit 		egyszerre ennyi elem jelenik meg az oldalon	
     * @param  integer  $stages 	a jelenlegi oldal mindkét oldalán hány elem legyen az oldalszámozásnál
     */
    function __construct($from_email, $from_name, $to_email, $to_name, $subject, $form_data, $template) {
        $this->from_name = $from_name;
        $this->from_email = $from_email;
        $this->to_email = $to_email;
        $this->to_name = $to_name;
        $this->subject = $subject;
        $this->template = $template;
        $this->form_data = $form_data;
    }

    /**
     * e-mail küldése
     *
     * 
     */
    public function send() {

        if ($this->use_smtp) {
            // küldés SMTP-vel
            $this->send_with_smtp();
        } else {
            // egyszerű küldés 
            $this->simple_send();
        }
    }

    /**
     * e-mail küldése SMTP-vel
     *
     * @return 
     */
    public function send_with_smtp() {

// küldés PHPMailer-el
        include(LIBS . '/PHPMailer/PHPMailerAutoload.php');

        $mail = new PHPMailer;

        $host = Config::get('email.server.smtp_host');
        $user_name = Config::get('email.server.smtp_username');
        $password = Config::get('email.server.smtp_password');
        $port = Config::get('email.server.smtp_port');
        $auth = Config::get('email.server.smtp_auth');


        //Enable SMTP debugging. 
        $mail->SMTPDebug = 0;
//Set PHPMailer to use SMTP.
        $mail->isSMTP();
//Set SMTP host name                          
        $mail->Host = $host;
//Set this to true if SMTP host requires authentication to send email
        $mail->SMTPAuth = $auth;
//Provide username and password     
        $mail->Username = $user_name;
        $mail->Password = $password;
//If SMTP requires TLS encryption then set it
        $mail->SMTPSecure = "tls";
//Set TCP port to connect to 
        $mail->Port = $port;

        $mail->CharSet = 'UTF-8'; //karakterkódolás beállítása
        $mail->WordWrap = 78; //sortörés beállítása (a default 0 - vagyis nincs)
        $mail->From = $this->from_email; //feladó e-mail címe
        $mail->FromName = $this->from_name; //feladó neve
        $mail->Subject = $this->subject; // Tárgy megadása
        $mail->isHTML(true); // Set email format to HTML                                  

        $body = $this->load_template_with_data($this->template, $this->form_data);

        $mail->Body = $body;

        $mail->addAddress($this->to_email, $this->to_name);     // Add a recipient (Name is optional)
        // $mail->addAddress($admin_email);
// final sending and check
        if ($mail->send()) {
            return true;
        } else {
            return false;
        }

        $mail->clearAddresses();
    }

    /**
     * e-mail küldése SMTP-vel
     *
     * @return 
     */
    public function simple_send() {

        $mail = new SimpleMail();
        $mail->setTo($this->to_email, $this->to_name);
        $mail->setSubject($this->subject);
        $mail->setFrom($this->from_email, $this->from_name);
        $mail->addMailHeader('Reply-To', $this->from_email, $this->from_name);
        $mail->addGenericHeader('MIME-Version', '1.0');
        $mail->addGenericHeader('Content-Type', 'text/html; charset="utf-8"');
        $mail->addGenericHeader('X-Mailer', 'PHP/' . phpversion());

        $body = $this->load_template_with_data($this->template, $this->form_data);

        $mail->setMessage($body);

        $mail->setWrap(100);
// final sending and check
        if ($mail->send()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * template betöltése adatokkal
     *
     * 
     */
    public function load_template_with_data($template, $form_data) {

        $body = file_get_contents('system/site/view/email/tpl_' . $template . '.php');
        foreach ($form_data as $key => $value) {
            $body = str_replace('{' . $key . '}', $value, $body);
        }
        return $body;
    }

}

?>