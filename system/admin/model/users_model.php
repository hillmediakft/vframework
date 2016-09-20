<?php

class Users_model extends Admin_model {

    /**
     * 	Legyen-e email visszaigazolós regisztráció
     * 	Értéke: true vagy false
     */
    private $email_verify;

    protected $table = 'users';

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
    public function user_data_query($user_id = null)
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
            'roles.role_name'
        ));
        $this->query->set_join('left', 'roles', 'users.user_role_id', '=', 'roles.role_id');
        
        if(!is_null($user_id)){
            $this->query->set_where('user_id', '=', $user_id);
        }

        return $this->query->select();
    }

    /**
     * Új felhasználó létrehozása
     */
    public function insert_user()
    {
        // adatok a $_POST tömbből
        $post_data = $this->request->get_post();

        // validátor objektum létrehozása
        $validate = new Validate();

        // szabályok megadása az egyes mezőkhöz (mező neve, label, szabály)
        $validate->add_rule('name', 'username', array(
            'required' => true,
            'min' => 2
        ));
        $validate->add_rule('first_name', 'userfirstname', array(
            'required' => true,
            'min' => 2
        ));
        $validate->add_rule('last_name', 'userlastname', array(
            'required' => true,
            'min' => 2
        ));
        $validate->add_rule('password', 'password', array(
            'required' => true,
            'min' => 6
        ));
        $validate->add_rule('password_again', 'password_again', array(
            'required' => true,
            'matches' => 'password'
        ));
        $validate->add_rule('email', 'email', array(
            'required' => true,
            'email' => true
            // 'max' => 64
        ));        

        // üzenetek megadása az egyes szabályokhoz (szabály_neve, üzenet)
        $validate->set_message('required', ':label_field_empty');
        $validate->set_message('min', ':label_too_short');
        $validate->set_message('matches', ':label_repeat_wrong');
        $validate->set_message('email', ':label_does_not_fit_pattern');
        //$validate->set_message('max', ':label_too_long');

        // mezők validálása
        $validate->check($post_data);

        // HIBAELLENŐRZÉS - ha valamilyen hiba van a form adataiban
        if(!$validate->passed()){
            foreach ($validate->get_error() as $value) {
                Message::set('error', $value);
            }
            return false;
        }
        else {
        // végrehajtás, ha nincs hiba 
            $data = array();
            $data['user_name'] = $this->request->get_post('name');
            $data['user_first_name'] = $this->request->get_post('first_name');
            $data['user_last_name'] = $this->request->get_post('last_name');
            $data['user_email'] = $this->request->get_post('email');
            $data['user_phone'] = $this->request->get_post('phone');

            if (empty($this->request->get_post('img_url'))) {
                $data['user_photo'] = Config::get('user.default_photo');
            } else {
                $path_parts = pathinfo($this->request->get_post('img_url'));
                $data['user_photo'] = $path_parts['filename'] . '.' . $path_parts['extension'];
            }

            $data['user_role_id'] = $this->request->get_post('user_group', 'integer');
            $data['user_provider_type'] = ($this->request->get_uri('area') == 'admin') ? 'admin' : null;

                // jelszó kompatibilitás library betöltése régebbi php verzió esetén
                $this->load_password_compatibility();
                // crypt the user's password with the PHP 5.5's password_hash() function, results in a 60 character
                // hash string. the PASSWORD_DEFAULT constant is defined by the PHP 5.5, or if you are using PHP 5.3/5.4,
                // by the password hashing compatibility library. the third parameter looks a little bit shitty, but that's
                // how those PHP 5.5 functions want the parameter: as an array with, currently only used with 'cost' => XX
                $hash_cost_factor = (Config::get('hash_cost_factor') !== null) ? Config::get('hash_cost_factor') : null;

            $data['user_password_hash'] = password_hash($this->request->get_post('password'), PASSWORD_DEFAULT, array('cost' => $hash_cost_factor));

                // ellenőrzés, hogy létezik-e már ilyen felhasználói név az adatbázisban
                $sth = $this->connect->prepare("SELECT COUNT(*) FROM users WHERE user_name = :user_name");
                $sth->execute(array(':user_name' => $data['user_name']));
                if ($sth->fetchColumn() == 1) {
                    Message::set('error', 'username_already_taken');
                    return false;
                }

                // ellenőrzés, hogy létezik-e már ilyen email cím az adatbázisban
                if(!is_null($data['user_email'])){
                  $sth = $this->connect->prepare("SELECT COUNT(*) FROM users WHERE user_email = :user_email");
                  $sth->execute(array(':user_email' => $data['user_email']));
                  if ($sth->fetchColumn() == 1) {
                    Message::set('error', 'user_email_already_taken');
                    return false;
                  }
                }

            // ha be van állítva e-mail ellenőrzéses regisztráció
            if ($this->email_verify === true) {
                // generate random hash for email verification (40 char string)
                $data['user_activation_hash'] = sha1(uniqid(mt_rand(), true));
                $data['user_active'] = 0;
            } else {
                $data['user_activation_hash'] = null;
                $data['user_active'] = 1;
            }
            // generate integer-timestamp for saving of account-creating date
            $data['user_creation_timestamp'] = time();


            // Új felhasználó adatainak beírása az adatbázisba
            $last_inserted_id = $this->query->insert($data);
            if (!$last_inserted_id) {
                Message::set('error', 'account_creation_failed');
                return false;
            }

            // Ezután jön az ellenörző email küldés (ha az $email_verify tulajdonság értéke true)
            // ha sikeres az ellenőrzés, visszatér true-val, ellenkező esetben a visszatér false-al
            if ($this->email_verify === true) {

                // ellenőrző email küldése, ha az ellenőrző email küldése sikertelen: felhasználó törlése az databázisból
                if ($this->_sendVerificationEmail($last_inserted_id, $data['user_email'], $data['user_activation_hash'])) {
                    Message::set('success', 'account_successfully_created');
                    return true;
                } else {
                    $this->query->reset();
                    $this->query->set_table(array('users'));
                    $this->query->delete('user_id', '=', $last_inserted_id);
                    Message::set('error', 'verification_mail_sending_failed');
                    return false;
                }
            }

            // ha nincs email ellenőrzés, és minden ellenőrzés sikeres, akkor visszatér true-val
            Message::set('success', 'user_successfully_created');
            return true;
        }
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
                    $mail = new SimpleMail();
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
     * Admin user törlése AJAX-al
     *
     * @param string $id     ez lehet egy szám, vagy felsorolás pl: 23 vagy 12,14,36
     */
    public function delete_user_AJAX($id)
    {
        // a sikeres törlések számát tárolja
        $success_counter = 0;
        // a sikeresen törölt id-ket tartalmazó tömb
        $success_id = array();
        
        // a sikertelen törlések számát tárolja
        $fail_counter = 0;

        // a paraméterként kapott stringből tömböt csinálunk a , karakter mentén
        $data_arr = explode(',', $id);

        // bejárjuk a $data_arr tömböt és minden elemen végrehajtjuk a törlést
        foreach ($data_arr as $value) {
            //átalakítjuk a integer-ré a kapott adatot
            $value = (int) $value;

            //lekérdezzük a törlendő user avatar képének a nevét, hogy törölhessük a szerverről
            $this->query->set_columns(array('user_photo'));
            $this->query->set_where('user_id', '=', $value);
            $photo_name = $this->query->select();

            //felhasználó törlése 
            $this->query->reset();
            //a delete() metódus integert (lehet 0 is) vagy false-ot ad vissza
            $result = $this->query->delete('user_id', '=', $value);

            if ($result !== false) {
                // ha a törlési sql parancsban nincs hiba
                if ($result > 0) {
                    //kép törlése, ha nem a default kép
                    if ($photo_name[0]['user_photo'] != Config::get('user.default_photo')) {
                        //kép file törlése a szerverről (ha az Util::del_file() falsot ad vissza nem tudtuk törölni a képet... hibaüzenet)
                        $path = Config::get('user.upload_path');
                        if (!Util::del_file($path . $photo_name[0]['user_photo'])) {
                            Message::log('user_photo_can_not_be_deleted');
                        }
                    }
                    //sikeres törlés
                    $success_counter += $result;
                    $success_id[] = $value;
                } else {
                    //sikertelen törlés
                    $fail_counter += 1;
                }
                //continue;
            } else {
                // ha a törlési sql parancsban hiba van
                return array(
                    'status' => 'error',                  
                    'message_error' => 'Hibas sql parancs: nem sikerult a DELETE lekerdezes az adatbazisbol!',                  
                );
            }
        }

        // üzenetek visszaadása
        $respond = array();
        $respond['status'] = 'success';
        
        if ($success_counter > 0) {
            $respond['message_success'] = $success_counter . ' felhasználó törölve.';
        }
        if ($fail_counter > 0) {
            $respond['message_error'] = $fail_counter . ' felhasználót már töröltek!';
        }

        // respond tömb visszaadása
        return $respond;
    }

    /**
     * 	Felhasználó adatainak módosítása
     *
     * @param  integer $user_id
     */
    public function update_user($user_id)
    {
        // adatok a $_POST tömbből
        $post_data = $this->request->get_post();

        // validátor objektum létrehozása
        $validate = new Validate();

        // szabályok megadása az egyes mezőkhöz (mező neve, label, szabály)
        $validate->add_rule('name', 'username', array(
            'required' => true,
            'min' => 2
        ));
        $validate->add_rule('first_name', 'userfirstname', array(
            'required' => true,
            'min' => 2
        ));
        $validate->add_rule('last_name', 'userlastname', array(
            'required' => true,
            'min' => 2
        ));
        
        // Jelszó ellenőrzés ha üres a password és az ellenőrző password mezö
        if (empty($this->request->get_post('password')) && empty($this->request->get_post('password_again'))) {
            $password_empty = true;
        } else {
            $validate->add_rule('password', 'password', array(
                'required' => true,
                'min' => 6
            ));
            $validate->add_rule('password_again', 'password_again', array(
                'required' => true,
                'matches' => 'password'
            ));
        }

        $validate->add_rule('email', 'email', array(
            'required' => true,
            'email' => true
            // 'max' => 64
        ));        

        // üzenetek megadása az egyes szabályokhoz (szabály_neve, üzenet)
        $validate->set_message('required', ':label_field_empty');
        $validate->set_message('min', ':label_too_short');
        $validate->set_message('matches', ':label_repeat_wrong');
        $validate->set_message('email', ':label_does_not_fit_pattern');
        //$validate->set_message('max', ':label_too_long');

        // mezők validálása
        $validate->check($post_data);

        // HIBAELLENŐRZÉS - ha valamilyen hiba van a form adataiban
        if(!$validate->passed()){
            foreach ($validate->get_error() as $value) {
                Message::set('error', $value);
            }
            return false;
        }
        else {
        // végrehajtás, ha nincs hiba	
            $data['user_name'] = $this->request->get_post('name');
            $data['user_first_name'] = $this->request->get_post('first_name');
            $data['user_last_name'] = $this->request->get_post('last_name');
            $data['user_phone'] = $this->request->get_post('phone');

            //ha nem létezik a $password_empty változó, vagyis nem üres mindkét password mező	
            if (!isset($password_empty)) {

                // jelszó kompatibilitás library betöltése régebbi php verzió esetén
                $this->load_password_compatibility();                
                // crypt the user's password with the PHP 5.5's password_hash() function, results in a 60 character
                // hash string. the PASSWORD_DEFAULT constant is defined by the PHP 5.5, or if you are using PHP 5.3/5.4,
                // by the password hashing compatibility library. the third parameter looks a little bit shitty, but that's
                // how those PHP 5.5 functions want the parameter: as an array with, currently only used with 'cost' => XX
                $hash_cost_factor = (Config::get('hash_cost_factor') !== null) ? Config::get('hash_cost_factor') : null;
                $data['user_password_hash'] = password_hash($this->request->get_post('password'), PASSWORD_DEFAULT, array('cost' => $hash_cost_factor));
            }

            $data['user_email'] = $this->request->get_post('email');

            if ($this->request->has_post('user_group')) {
                $data['user_role_id'] = $this->request->get_post('user_group', 'integer');
            }

            //ha van feltöltve user kép
            if (!empty($this->request->get_post('img_url'))) {
                $path_parts = pathinfo($this->request->get_post('img_url'));
                $data['user_photo'] = $path_parts['filename'] . '.' . $path_parts['extension'];
            }

            // Megvizsgáljuk, hogy van-e már ilyen nevű user (de nem az amit módosítani akarunk)
            $this->query->set_columns(array('user_id'));
            $this->query->set_where('user_name', '=', $data['user_name']);
            //itt megadjuk, hogy nem vonatkozik a bejelentkezett user-re (mert ha nem módosítja a nevet akkor már van ilyen user név)
            $this->query->set_where('user_id', '!=', $user_id);
            $result = $this->query->select();

            // ha már van ilyen nevű felhasználó
            if (count($result) == 1) {
                Message::set('error', 'username_already_taken');
                return false;
            }

            /* 			
              if(!is_null($data['user_email'])){
              // Megvizsgáljuk, hogy van-e már ilyen email cím (de nem az amit módosítani akarunk)
              $this->query->reset();
              $this->query->set_table(array('users'));
              $this->query->set_columns(array('user_email'));
              $this->query->set_where('user_email', '=', $data['user_email']);
              //itt megadjuk, hogy nem vonatkozik a bejelentkezett user-re (mert ha nem módosítja a nevet akkor már van ilyen user név)
              $this->query->set_where('user_id', '!=', $user_id);
              $result = $this->query->select();

              // ha már van ilyen email cím
              if (count($result) == 1) {
              Message::set('error', 'user_email_already_taken');
              return false;
              }
              }
             */


            // új adatok beírása az adatbázisba (update) a $data tömb tartalmazza a frissítendő adatokat 
            $this->query->set_where('user_id', '=', $user_id);
            $result = $this->query->update($data);

            if ($result >= 0) {
                // ha a bejelentkezett user adatait módosítjuk, akkor a session adatokat is frissíteni kell
                if (Session::get('user_id') == $user_id) {
                    // Módosítjuk a $_SESSION tömben is a user adatait!
                    Session::set('user_name', $data['user_name']);
                    Session::set('user_email', $data['user_email']);
                    if (isset($data['user_role_id'])) {
                        Session::set('user_role_id', $data['user_role_id']);
                    }
                    if (isset($data['user_photo'])) {
                        Session::set('user_photo', $data['user_photo']);
                    }
                }
                Message::set('success', 'user_data_update_success');
                return true;
            } else {
                Message::set('error', 'unknown_error');
                return false;
            }
        } 
    }

    /**
     * 	Felhasználó képének vágása és feltöltése
     * 	Az $this->registry->params['id'] paraméter értékétől függően feltölti a kiválasztott képet
     * 		upload paraméter esetén: feltölti a kiválasztott képet
     * 		crop paraméter esetén: megvágja a kiválasztott képet és feltölti	
     */
    public function user_img_upload()
    {
        if ($this->request->has_params('id')) {
            //include(LIBS . "/upload_class.php");
            // Kiválasztott kép feltöltése
            if ($this->request->get_params('id') == 'upload') {

                // feltöltés helye
                $imagePath = Config::get('user.upload_path');

                //képkezelő objektum létrehozása (a kép a szerveren a tmp könyvtárba kerül)	
                $handle = new Upload($_FILES['img']);

                if ($handle->uploaded) {
                    // kép paramétereinek módosítása
                    $handle->file_auto_rename = true;
                    $handle->file_safe_name = true;
                    //$handle->file_new_name_body   	 = 'lorem ipsum';
                    $handle->allowed = array('image/*');
                    $handle->image_resize = true;
                    $handle->image_x = Config::get('user.width', 600);
                    $handle->image_ratio_y = true;

                    //végrehajtás: kép átmozgatása végleges helyére
                    $handle->Process($imagePath);

                    if ($handle->processed) {
                        //temp file törlése a szerverről
                        $handle->clean();

                        $response = array(
                            "status" => 'success',
                            "url" => $imagePath . $handle->file_dst_name,
                            "width" => $handle->image_dst_x,
                            "height" => $handle->image_dst_y
                        );
                        return json_encode($response);
                    } else {
                        $response = array(
                            "status" => 'error',
                            "message" => $handle->error . ': Can`t upload File; no write Access'
                        );
                        return json_encode($response);
                    }
                } else {
                    $response = array(
                        "status" => 'error',
                        "message" => $handle->error . ': Can`t upload File; no write Access'
                    );
                    return json_encode($response);
                }
            }


            // Kiválasztott kép vágása és vágott kép feltöltése
            if ($this->request->get_params('id') == 'crop') {

                // a croppic js küldi ezeket a POST adatokat 	
                $imgUrl = $this->request->get_post('imgUrl');
                // original sizes
                $imgInitW = $this->request->get_post('imgInitW');
                $imgInitH = $this->request->get_post('imgInitH');
                // resized sizes
                //kerekítjük az értéket, mert lebegőpotos számot is kaphatunk és ez hibát okozna a kép generálásakor
                $imgW = round($this->request->get_post('imgW'));
                $imgH = round($this->request->get_post('imgH'));
                // offsets
                // megadja, hogy mennyit kell vágni a kép felső oldalából
                $imgY1 = $this->request->get_post('imgY1');
                // megadja, hogy mennyit kell vágni a kép bal oldalából
                $imgX1 = $this->request->get_post('imgX1');
                // crop box
                $cropW = $this->request->get_post('cropW');
                $cropH = $this->request->get_post('cropH');
                // rotation angle
                //$angle = $this->request->get_post('rotation');
                //a $right_crop megadja, hogy mennyit kell vágni a kép jobb oldalából
                $right_crop = ($imgW - $imgX1) - $cropW;
                //a $bottom_crop megadja, hogy mennyit kell vágni a kép aljából
                $bottom_crop = ($imgH - $imgY1) - $cropH;

                // feltöltés helye
                $imagePath = Config::get('user.upload_path');

                //képkezelő objektum létrehozása (a feltöltött kép elérése a paraméter)	
                $handle = new Upload($imgUrl);

                // fájlneve utáni random karakterlánc
                $suffix = md5(uniqid());

                if ($handle->uploaded) {

                    // kép paramétereinek módosítása
                    //$handle->file_auto_rename 		 = true;
                    //$handle->file_safe_name 		 = true;
                    //$handle->file_name_body_add   	 = '_thumb';
                    $handle->file_new_name_body = "user_" . $suffix;
                    //kép átméretezése
                    $handle->image_resize = true;
                    $handle->image_x = $imgW;
                    $handle->image_ratio_y = true;
                    //utána kép vágása
                    $handle->image_crop = array($imgY1, $right_crop, $bottom_crop, $imgX1);

                    //végrehajtás: kép átmozgatása végleges helyére
                    $handle->Process($imagePath);

                    if ($handle->processed) {
                        // vágatlan forrás kép törlése az upload/user_photo mappából
                        $handle->clean();

                        $response = array(
                            "status" => 'success',
                            "url" => $imagePath . $handle->file_dst_name
                        );
                        return json_encode($response);
                    } else {
                        $response = array(
                            "status" => 'error',
                            "message" => $handle->error . ': Can`t upload File; no write Access'
                        );
                        return json_encode($response);
                    }
                } else {
                    $response = array(
                        "status" => 'error',
                        "message" => $handle->error . ': Can`t upload File; no write Access'
                    );
                    return json_encode($response);
                }
            }
        }
    }

    /**
     * 	(AJAX) A users tábla user_active mezőjének ad értéket
     * 	
     * 	@param	integer	$id	
     * 	@param	integer	$data (0 vagy 1)	
     * 	@return bool
     */
    public function change_status_query($id, $data)
    {
        $this->query->set_where('user_id', '=', $id);
        $result = $this->query->update(array('user_active' => $data));
        return ($result) ? true : false;
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