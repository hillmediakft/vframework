<?php
namespace System\Libs;
use System\Libs\Config;

/**
 * 	Emailer class v1.1
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
     * Adatok a template-be
     */
    private $template_data;

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
     * Csatolmányok file-nevei
     */
    private $attachments = array();

    /**
     * Csatolmányok elérési útja
     */
    private $attachments_path = '';

    /**
     * SMTP kapcsoló
     */
    private $use_smtp = false;

    /**
     * site vagy admin
     */
    private $area = 'Site';

    /**
     * debug
     */
    private $debug = false;

    /**
     * CONSTRUCTOR
     *
     * @param  string   $from_email
     * @param  string   $from_name
     * @param  string   $to_email
     * @param  string   $to_name
     * @param  string   $subject
     * @param  string   $template_data
     * @param  string   $template
     */
    public function __construct($from_email, $from_name, $to_email, $to_name, $subject, $template_data, $template, $attachments = array())
    {
        $this->from_name = $from_name;
        $this->from_email = $from_email;
        $this->to_email = $to_email;
        $this->to_name = $to_name;
        $this->subject = $subject;
        $this->template = $template;
        $this->template_data = $template_data;
        $this->attachments = $attachments;

        $this->use_smtp = Config::get('email.server.use_smtp', false);
    }

    /**
     * use_smtp tulajdonság értékének beállítása
     * @param bool
     */
    public function setSmtp($use_smtp = true)
    {
        $this->use_smtp = (bool)$use_smtp;
    }

    /**
     * Area tulajdonság értékének beállítása (site vagy admin, ahonnan betölti a template-et)
     * @param bool
     */
    public function setArea($area)
    {
        $this->area = $area;
    }

    /**
     * Debug tulajdonság értékének beállítása (ha true ... akkor echo template)
     * @param bool
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    /**
     * e-mail küldése SMTP-vel
     *
     * @return bool
     */
    public function send()
    {
        //include(LIBS . '/PHPMailer/PHPMailerAutoload.php');
        $mail = new \PHPMailer;

        if ($this->use_smtp) {
            //Set PHPMailer to use SMTP.
            $mail->isSMTP();
            //Enable SMTP debugging. 
            $mail->SMTPDebug = Config::get('email.server.phpmailer_debug_mode');
            //Set SMTP host name                          
            $mail->Host = Config::get('email.server.smtp_host');
            //Set this to true if SMTP host requires authentication to send email
            $mail->SMTPAuth = Config::get('email.server.smtp_auth');
// SMTP connection will not close after each email sent, reduces SMTP overhead
// $mail->SMTPKeepAlive = true;
            //Provide username and password     
            $mail->Username = Config::get('email.server.smtp_username');
            $mail->Password = Config::get('email.server.smtp_password');
            //If SMTP requires TLS encryption then set it
            $mail->SMTPSecure = Config::get('email.server.smtp_encryption');
            //Set TCP port to connect to 
            $mail->Port = Config::get('email.server.smtp_port');
        } 
        else {
            $mail->isMail(); // küldés a php mail metódusával
            // $mail->isSendmail(); // küldés sendmail-al
        }

        $mail->CharSet = 'UTF-8'; //karakterkódolás beállítása
        $mail->WordWrap = 78; //sortörés beállítása (a default 0 - vagyis nincs)
        $mail->From = $this->from_email; //feladó e-mail címe
        $mail->FromName = $this->from_name; //feladó neve
        $mail->Subject = $this->subject; // Tárgy megadása
        $mail->isHTML(true); // Set email format to HTML                                  

        if (!empty($this->attachments)) {
            foreach ($this->attachments as $value) {
                $mail->addAttachment($this->attachments_path . $value);
            }
        }

// levél tartalom beállítása
        $mail->Body = $this->_load_template_with_data($this->template, $this->template_data);
        //$mail->AltBody = '';

if ($this->debug) {
    echo $this->_load_template_with_data($this->template, $this->template_data);
    return false;
}

        $mail->addAddress($this->to_email, $this->to_name);     // Add a recipient (Name is optional)
        // $mail->addAddress($admin_email);

// final sending and check
        if ($mail->send()) {
            $mail->clearAddresses();
            return true;
        } else {
            $mail->clearAddresses();
            //var_dump($mail->ErrorInfo);
            return false;
        }
    }

    /**
     * e-mail küldése SimpleMail-el
     *
     * @return 
     */
    public function sendSimple()
    {
        //include(LIBS . '/simplemail_class.php');    
        $mail = new SimpleMail();
        $mail->setTo($this->to_email, $this->to_name);
        $mail->setSubject($this->subject);
        $mail->setFrom($this->from_email, $this->from_name);
        $mail->addMailHeader('Reply-To', $this->from_email, $this->from_name);
        $mail->addGenericHeader('MIME-Version', '1.0');
        $mail->addGenericHeader('Content-Type', 'text/html; charset="utf-8"');
        $mail->addGenericHeader('X-Mailer', 'PHP/' . phpversion());

        $body = $this->_load_template_with_data($this->template, $this->template_data);
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
     * @param string $template
     * @param array $template_data
     * @return string 
     */
    private function _load_template_with_data($template, $template_data)
    {
        $body = file_get_contents('system/' . ucfirst($this->area) . '/view/email/tpl_' . $template . '.php');
        foreach ($template_data as $key => $value) {
            $body = str_replace('{' . $key . '}', $value, $body);
        }
        return $body;
    }

}
?>