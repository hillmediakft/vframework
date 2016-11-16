<?php
namespace System\Admin\Model;
use System\Core\Admin_model;
use System\Libs\Config;

class User_model extends Admin_model {

    /**
     * 	Legyen-e email visszaigazolós regisztráció
     * 	Értéke: true vagy false
     */
    private $email_verify;

    // tábla neve
    protected $table = 'users';
    // id neve
    protected $id = 'user_id';

    /**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
    function __construct()
    {
        parent::__construct();

        //regisztráció email-es ellenőrzésének be- vagy kikapcsolása
        //$this->email_verify = Config::get('reg_email_verify', true);
        $this->email_verify = false;
    }

    /**
     *  Felhasználók adatainak lekérdezése
     *
     *  @param  string|integer    $user_id (csak ennek a felhasználónak az adatait adja vissza
     *  @return array|false
     */
    public function selectUser($user_id = null)
    {
        $this->query->set_columns(array(
            'users.user_id',
            'users.user_name',
            'users.user_first_name',
            'users.user_last_name',
            'users.user_active',
            'users.user_email',
            'users.user_role_id',
            'users.user_phone',
            'users.user_photo',
            'roles.role'
        ));
        $this->query->set_join('left', 'roles', 'users.user_role_id', '=', 'roles.id');
        
        if(!is_null($user_id)){
            $this->query->set_where('user_id', '=', $user_id);
            $result = $this->query->select();
            return $result[0];
        } else {
            return $this->query->select();
        }
    }

    /**
     * ellenőrzés, hogy létezik-e már ilyen felhasználói név az adatbázisban
     */
    public function checkUsername($username)
    {
        $sth = $this->connect->prepare("SELECT COUNT(*) FROM `users` WHERE user_name = :user_name");
        $sth->execute(array(':user_name' => $username));
        if ($sth->fetchColumn() == 1) {
            return true;
        }
        return false;
    }

    /**
     * Megvizsgáljuk, hogy van-e már ilyen nevű user, de nem az amit módosítani akarunk
     */
    public function checkUserNoLoggedIn($id, $username)
    {
        $this->query->set_columns(array('user_id'));
        $this->query->set_where('user_name', '=', $username);
        //itt megadjuk, hogy nem vonatkozik a bejelentkezett user-re (mert ha nem módosítja a nevet akkor már van ilyen user név)
        $this->query->set_where('user_id', '!=', $id);
        $result = $this->query->select();
        // ha már van ilyen nevű felhasználó
        if (count($result) == 1) {
            return true;
        }
        return false;
    }

    /**
     * ellenőrzés, hogy létezik-e már ilyen email cím az adatbázisban
     */
    public function checkEmail($useremail)
    {
        $sth = $this->connect->prepare("SELECT COUNT(*) FROM users WHERE user_email = :user_email");
        $sth->execute(array(':user_email' => $useremail));
        if ($sth->fetchColumn() == 1) {
            return true;
        }    
        return false;
    }

    /**
     * Megvizsgáljuk, hogy van-e már ilyen email cím (de nem az amit módosítani akarunk)
     */
    public function checkEmailNoLoggedIn($id, $email)
    {
        $this->query->set_columns(array('user_email'));
        $this->query->set_where('user_email', '=', $email);
        //itt megadjuk, hogy nem vonatkozik a bejelentkezett user-re (mert ha nem módosítja a nevet akkor már van ilyen user név)
        $this->query->set_where('user_id', '!=', $id);
        $result = $this->query->select();
        if (count($result) == 1) {
            return true;
        }
        return false;                
    }

    /**
     * INSERT
     */
    public function insert($data)
    {
        return $this->query->insert($data);        
    }

    /**
     * UPDATE
     */
    public function update($id, $data)
    {
        $this->query->set_where('user_id', '=', $id);
        return $this->query->update($data);        
    }

    /**
     * DELETE
     */
    public function delete($id)
    {
        return $this->query->delete('user_id', '=', $id);        
    }

    /**
     * User fotójának lekérdezése
     */
    public function selectPicture($id)
    {
        $this->query->set_columns(array('user_photo'));
        $this->query->set_where('user_id', '=', $id);
        $result = $this->query->select();
        return $result[0]['user_photo'];
    }


                /**
                 * sends an email to the provided email address
                 *
                 * @param string    $user_name                  felhasznalo neve
                 * @param int       $user_id                    user's id
                 * @param string    $user_email                 user's email
                 * @param string    $user_activation_hash       user's mail verification hash string

                 * @return boolean
                 */
                private function _sendVerificationEmail($user_name, $user_id, $user_email, $user_activation_hash)
                {
                    // Email kezelő osztály behívása
                    include(LIBS . '/simple_mail_class.php');

                    $subject = Config::get('email.verification.subject');
                    $link = Config::get('email.verification.link');
                    $html = '<html><body><h3>Kedves ' . $user_name . '!</h3><p>A ' . $user_email . ' e-mail címmel regisztráltál a ---. Regisztrációd megtörtént, de jelenleg passzív.</p><a href="' . BASE_URL . 'regisztracio/' . $user_id . '/' . $user_activation_hash . '">' . $link . '</a><p>Az aktiválást követően a ----- oldalára jutsz, ahol bejelentkezhetsz a felhasználó neveddel és jelszavaddal. Annak érdekében, hogy segíthessünk a számodra leginkább megfelelő munka megtalálásában, töltsd ki a felhasználói profilodat. </p><p>Üdvözlettel:<br>A Multijob Diákszövetkezet csapata</p></body></html>';
                    
                    $from_email = Config::get('email.from_email');
                    $from_name = Config::get('email.from_name');
                    
                    // Létrehozzuk a SimpleMail objektumot
                    $mail = new \System\Libs\SimpleMail();
                    $mail->setTo($user_email, $user_name)
                         ->setSubject($subject)
                         ->setFrom($from_email, $from_name)
                         ->addMailHeader('Reply-To', 'info@gmail.com', 'Mail Bot')
                         ->addGenericHeader('MIME-Version', '1.0')
                         ->addGenericHeader('Content-Type', 'text/html; charset="utf-8"')
                         ->addGenericHeader('X-Mailer', 'PHP/' . phpversion())
                         ->setMessage($html)
                         ->setWrap(78);
              
                    // final sending and check
                    if($mail->send()) {
                        return true;
                    } else {
                        return false;
                    }
                }

                /**
                 * checks the email/verification code combination and set the user's activation status to true in the database
                 * @param int $user_id user id
                 * @param string $user_activation_verification_code verification token
                 * @return bool success status
                 */
                public function verifyNewUser($user_id, $user_activation_verification_code)
                {
                    // megnézzük, hogy már sikerült-e a regisztráció (ha frissíti az oldalt)
                    $this->query->set_columns(array('user_id'));
                    $this->query->set_where('user_id', '=', $user_id);
                    $this->query->set_where('user_active', '=', 1, 'and');
                    $this->query->set_where('user_activation_hash', '=', null, 'and');
                    $result = $this->query->select();

                    if($result){
                        return true;
                    }
                            
                    $data['user_active'] = 1;
                    $data['user_activation_hash'] = null;
                    
                    $this->query->set_where('user_id', '=', $user_id);
                    $this->query->set_where('user_activation_hash', '=', $user_activation_verification_code, 'and');
                    $result = $this->query->update($data);
                    
                    if ($result == 1) {
                        return true;
                    } else {
                        return false;
                    }
                }


    /**
     * 	(AJAX) A users tábla user_active mezőjének ad értéket
     * 	
     * 	@param	integer	$id	
     * 	@param	integer	$data (0 vagy 1)	
     * 	@return bool
     */
    public function changeStatus($id, $data)
    {
        $this->query->set_where('user_id', '=', $id);
        return $this->query->update(array('user_active' => $data));
    }

    /**
     * Megviszgálja, hogy az adott id-jű user szuperadmin-e 
     *
     * @return true ha superadmin, false ha nem
     */
    public function is_user_superadmin($id)
    {
        $this->query->set_columns(array('user_role_id'));
        $this->query->set_where('user_id', '=', $id);
        $result = $this->query->select();
        return ($result[0]['user_role_id'] == '1') ? true : false;
    }

    /**
     * 	Visszaadja a userss tábla user_role_id oszlop tartalmát
     * 	A felhasználói szerepek számának meghatározásához kell
     */
    public function rolesCounter()
    {
        $this->query->set_columns('user_role_id');
        return $this->query->select();
    }




    /*
     * Lekérdezzük egy bizonyos e-mail címmel rendelkező user nevét és password-ját (elfelejtett jelszó esetén)
     *
     *  @param  string  $email_address
     */
    public function getPasswordHash($email_address)
    {
        $this->query->set_columns(array('user_name', 'user_password_hash'));
        $this->query->set_where('user_email', '=', $email_address);
        return $this->query->select();
    }

    /*
     * Új jelszó adatbázisba írása (elfelejtett jelszó esetén)
     *
     * @param  string  $email_address
     * @param string  $password_hash
     */
    public function setNewPassword($email_address, $password_hash)
    {
        $this->query->set_where('user_email', '=', $email_address);
        return $this->query->update(array('user_password_hash' => $password_hash));
    }    


} // end class
?>