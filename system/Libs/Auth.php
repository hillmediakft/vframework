<?php
namespace System\Libs;

use PDO;

/**
* Class Auth
* v2.1
*
*   Publikus statikus metódusok:
*       
*       Auth::init(); - Auth beállítások betöltése a config tömbbe, area megadása
*       DI konténer esetén nincs használatban! Auth::instance(); - Visszadja az Auth objektumot
*       Auth::check(); - Ellenőrzi, hogy be van-e jelentkezve a felhasználó és lejárt e a munkamenet időkorlát
*       Auth::isUserLoggedIn(); - Ellenőrzi, hogy be van-e jelentkezve a felhasználó (a session user_logged_in elem meglétét és tartalmát vizsgálja)
*       Auth::getUser('valamilyen_adat'); - Bejelentkezett user paraméterben megadott adatának visszaadása a 'data_user' session elemből
*       Auth::getRoleId(); - A bejelentkezett felhasználó role_id-jét adja vissza a sessionből
*       Auth::isSuperadmin(); - Megnézi, hogy a bejelentkezett felhasználó szuperadmin-e
* ACL:       
*       Auth::hasAccess('valamilyen_művelet', 'átirányítási url'); - Felhasználó engedélyének ellenőrzése a megadott művlethez
*
*   Publikus metódusok:
*       Auth::login('username_or_email', 'password', 'rememberme'); - Felhasználó bejelentkezése
*       Auth::loginWithCookie(); - Felhasználó bejelentkezése rememberme cookie-val
*       Auth::logout(); - kijelentkezés, session destroy    
*       Auth::deleteCookie(); - rememberme cookie törlése
*       Auth::getUserData('username_or_email'); - Visszadja annak a felhasználónak az adatait, ahol a felhasználói név vagy az email-cím egyezik a paraméterban kapott adattal
*       Auth::getUserDataById('user_id', 'rememberme_token'); - Visszadja a megadott id-vel rendelkező felhasználó adatait    
*       Auth::getPerms(); - Visszadja a permissions tulajdonság értékét
*       Auth::setError(); - Hiba megadása a hibákat tartalmazó tömbbe
*       Auth::getError(); - Hiba tömb  visszaadása
*       Auth::checkError(); - Hiba tömb vizsgálata
* ACL:
*       Auth::getRoles(); - Visszaadja a megadott role_id-jű csoport mindent adatát (paraméter nélkül minden adatot) a roles táblából
*       Auth::getRolePerms($role_id); - Visszaadja a paraméterben megadott felhasználói csoport engedélyezett permission kulcsait
*       Auth::getAllPerms(); - Visszaadja az összes permissions-t (a permissions tábla `id`, `key`, `desc` oszlopát)
*       Auth::savePerms(); - Engedélyek mentése
*       Auth::checkPermission(); - Megvizsgálja, hogy létezik-e a megadott permission a permissions táblában
*
*/
class Auth {
    
    // Az auth objektumot tárolja_
    private static $_instance = null;

    // PDO kapcsolat objektum
    protected $connect;

    // A bejelentkezéskor lekérdezett felhasználó adatait tárolja
    protected $user = null;

    //private $user_role_id = null;
    // Request objektum
    //protected $request;

    // Query objektum
    //protected $query;

    // hibaüzeneteket tartalmazó tőmb
    protected $error = array();

    // felhasználókat tartalmazó db tábla neve
    protected $table_name;
    // a felhasználókat tartalmazó tábla - felhasználók neveit tartalmazó oszlopának neve
    protected $username_colname;
    // a felhasználókat tartalmazó tábla - email címeket tartalmazó oszlopának neve
    protected $email_colname;
    // a felhasználókat tartalmazó tábla - jelszót tartalmazó oszlopának neve
    protected $password_colname;

    // munkamenet lejárati ideje
    private static $session_expire_time = 7200;
    // a bejelentkezés állapotát tároló kulcs a session-ben
    private static $logged_in = 'user_logged_in';
    // admin vagy site
    private static $area;
    // felhasználó utolsó aktivitási timestamp-jét tároló session tömbelem kulcsa
    private static $last_activity = 'user_last_activity';

    // ACL: Ez a tömb tárolja a felhasználói csoportnak engedélyezett elemeket
    public $permissions = null;


    /**
     * @var  array  value for guest login
     */
/*    
    protected $guest_login = array(
        'id' => 0,
        'name' => 'guest',
        'role_id' => '0',
        'email' => false
    );
*/


    public function __construct()
    {
        $this->connect = DI::get('connect');
        //$this->query = new Query($this->connect);
        
        // a felhasználókat tartalmazó tábla neve
        $this->table_name = Config::get('auth.table_name', 'users');
        $this->username_colname = Config::get('auth.username_colname', 'name');
        $this->email_colname = Config::get('auth.email_colname', 'email');
        $this->password_colname = Config::get('auth.password_colname', 'password');
    }
   

// -------- STATIKUS METÓDUSOK ----------------

    /**
     * Auth beállítások betöltése a config tömbbe
     *
     * @param string $auth_config (az auth config file neve)
     * @param string $area (admin vagy site)
     */
    public static function init($auth_config)
    {
        Config::load($auth_config);
        self::$session_expire_time = Config::get('auth.session_expire_time', 3600);
        self::$area = AREA;
    }

    /**
     * Visszadja az Authenticate objektumot
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     *  Ellenőrzi, hogy be van-e jelentkezve a felhasználó és lejárt e a munkamenet időkorlát
     *
     * @param bool $destroy - ha false, akkor nem lesz session destroy, csak a login, és user elemekt törli 
     * @return bool
     */
    public static function check($destroy = true)
    {
        //$instance = self::instance();

        // initialize the session
        Session::init();

        // Ha a felhasználó  be van jelentkezve
        // if (Session::has(self::$logged_in) && Session::get(self::$logged_in) === true) {
        if (self::isUserLoggedIn()) {
       
            // ha admin felületre akar belépni a felhasználó, akkor megvizsgáljuk, hogy van-e jogosultsága hozzá
            if(self::$area == 'admin') {
                if(Session::get('user_data.provider_type') !== 'admin') {
                    Message::set('error', 'Nincs jogosultsága az oldalra való belépéshez!');
                    return false;
                }
            }

            // utolsó aktivitás elem vizsgálata 
            /*
            if(!Session::has(self::$last_activity)) {
                throw new \Exception('Nem letezik a $_SESSION tombben utolso aktivitas elem!');
                exit;
            }
            */

            // ha a felhasználó be van jelentkezve, megnézzük hogy nem járt-e le az időkorlát (ha igen session törlés, üzenet beállítása)
            if(Session::get(self::$last_activity) < (time() - self::$session_expire_time)){
                // ha lejárt az időkorlát
                Session::destroy();
                Session::init();
                //Session::set(self::$logged_in, false);
                Message::set('error', 'A munkamenet lejárt!');
                return false;

            } else {
                Session::set(self::$last_activity, time());
                return true;
            } 
        
        } else {
            // ha nincs bejelentkezve a felhasználó
            if ($destroy) {
                Session::destroy();
                return false;
            } else {
                // töröljük a logged_in elemet és a user adatait    
                Session::delete(self::$logged_in);
                // töröljük a user adatokat    
                Session::delete('user_data');
                return false;
            }    
        }
    }

    /**
     * Munkamenet időkorlát lejáratának ellenőrzése
     */
    public static function checkExpire()
    {
        // ha lejárt az időkorlát
        if(Session::get(self::$last_activity) < (time() - self::$session_expire_time)){
            // töröljük a logged_in elemet és a user adatait    
            Session::delete(self::$logged_in);
            // töröljük a user adatokat    
            Session::delete('user_data');
            return false;

        } else {
            Session::set(self::$last_activity, time());
            return true;
        }                
    }

    /**
     * Ellenőrzi, hogy be van-e jelentkezve a felhasználó (a session user_logged_in elem meglétét és tartalmát vizsgálja)
     * @return bool 
     */
    public static function isUserLoggedIn()
    {
        if (!Session::has(self::$logged_in)) {
            return false;
        }    
        return Session::get(self::$logged_in);
    }

    /**
     * Bejelentkezett user valamilyen adatának visszaadása a session-ből
     *   
     * @param string $data
     */
    public static function getUser($data = null)
    {
        if (Session::has('user_data')) {
            if (is_null($data)) {
                return Session::get('user_data');
            } else {
                return Session::get('user_data.' . $data);
            }
        }
    }

    /**
     * Ellenőrzi, hogy a bejelentkezett felhasználó szuperadmin
     */
    public static function isSuperadmin()
    {
        return (self::getUser('role_id') == 1) ? true : false;
    }


// -------- OBJEKTUM METÓDUSOK ----------------


    /**
     * A bejelentkezett felhasználó role_id-jét adja vissza
     */
    public function getRoleId()
    {
        return Session::get('user_data.role_id');
    }

    /**
     * Visszadja a permissions tulajdonság értékét
     */
    public function getPerms()
    {
        return $this->permissions;
    }
    
    /**
     * Felhasználó bejelentkezése
     *
     * @param string $username_or_email
     * @param string $password
     * @param bool $rememberme
     * @return bool
     */
    public function login($username_or_email, $password, $rememberme = false)
    {
        // beviteli mezők validálás        
        if (empty($username_or_email)) {
            $this->setError('username_field_empty');
            return false;
        }
        if (empty($password)) {
            $this->setError('password_field_empty');
            return false;
        }

    // user adatainak lekérdezése
        $this->user = $this->getUserData($username_or_email);

    // ha nincs ilyen user
        if(!$this->user) {
            $this->setError('login_failed');
            return false;
        }
    // block login attempt if somebody has already failed 3 times and the last login attempt is less than 30sec ago
        if (($this->user->failed_logins >= 3) AND ($this->user->last_failed_login > (time()-30))) {
            $this->setError('password_wrong_3_times');
            return false;
        }
    // ha hibás a jelszó
        if (!$this->_verifyPassword($password, $this->user->password_hash)) {
            $this->_registerFailedLogin($username_or_email);
            return false;
        }
    // a user_active mező értékének vizsgálata         
        if (!$this->_isActive($this->user->active)) {
            $this->setError('Az ön belépési engedélye fel van függesztve!');
            return false;
        }

    // user login adatok eltárolása session-ben
        $user_data = array(
            'id' => $this->user->id,
            'name' => $this->user->name,
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'email' => $this->user->email,
            'role_id' => $this->user->role_id,
            'photo' => $this->user->photo,
            'provider_type' => $this->user->provider_type
        );

        Session::init();
        Session::set(self::$logged_in, true);
        Session::set(self::$last_activity, time()); // bejelentkezés, illetve utolsó aktivitás ideje
        Session::set('user_data', $user_data);


        // adatok a pdo objektum execute metódusának
        $data = array();

        // Ha a flhasználó bejelölte a "remember me" checkbox-ot, akkor létrehozunk egy cookie-t
        if ($rememberme) {
            // generálunk egy 64 karakter hosszú string-et
            $random_token_string = hash('sha256', mt_rand());
            // adat a pdo objektum execute metódusának    
            $data[':rememberme_token'] = $random_token_string; 

            // generate cookie string that consists of user id, random string and combined hash of both
            $cookie_string_first_part = $this->user->id . ':' . $random_token_string;
            $cookie_string_hash = hash('sha256', $cookie_string_first_part);
            $cookie_string = $cookie_string_first_part . ':' . $cookie_string_hash;

            // cookie beállítása
            setcookie('rememberme', $cookie_string, time() + Config::get('cookie.runtime'), '/');
            // setcookie('rememberme', $cookie_string, time() + Config::get('cookie.runtime'), "/", Config::get('cookie.domain'));
        }

        // generate integer-timestamp for saving of last-login date
        $last_login_timestamp = time();
        // adatok a pdo objektum execute metódusának    
        $data[':last_login_timestamp'] = $last_login_timestamp; 
        $data[':id'] = $this->user->id;

        // sikeres bejelentkezés esetén adatok rögzítése az adatbázisban
        $this->_registerSuccessLogin($data, $rememberme);

        return true;
    }

    /**
     * Bejelentkezés cookie-val
     */
    public function loginWithCookie()
    {
        $cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : false;
        //ha nem létezik 'rememberme' cookie
        if (!$cookie) {
            // $this->setError('cookie_invalid');
            return false;
        }
        // cookie tartalom elemekre bontása
        list ($user_id, $token, $hash) = explode(':', $cookie);
        // ha a 'rememberme' cookie első két részének titkosított kódja nem egyezik meg a cookie harmadik részével
        if ($hash !== hash('sha256', $user_id . ':' . $token)) {
            $this->setError('cookie_invalid');
            return false;
        }
        // ha üres a cookie token része
        if (empty($token)) {
            $this->setError('cookie_invalid');
            return false;
        }

    // user adatainak lekérdezése
        $this->user = $this->getUserDataById($user_id, $token);

    // ha nincs ilyen user
        if(!$this->user) {
            $this->setError('login_failed');
            return false;
        }
    // a user_active mező értékének vizsgálata         
        if (!$this->_isActive($this->user->active)) {
            $this->setError('Az ön belépési engedélye fel van függesztve!');            
            return false;
        }

    // user login adatok eltárolása session-ben
        $user_data = array(
            'id' => $this->user->id,
            'name' => $this->user->name,
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'email' => $this->user->email,
            'role_id' => $this->user->role_id,
            'photo' => $this->user->photo,
            'provider_type' => $this->user->provider_type
        );

        Session::init();
        Session::set(self::$logged_in, true);
        Session::set(self::$last_activity, time()); // bejelentkezés, illetve utolsó aktivitás ideje
        Session::set('user_data', $user_data);


        // generate integer-timestamp for saving of last-login date
        $last_login_timestamp = time();
        // adatok a pdo objektum execute metódusának    
        $data = array(
            ':id' => $this->user->id,
            ':last_login_timestamp' => $last_login_timestamp
            );

        // sikeres bejelentkezés esetén adatok rögzítése az adatbázisban (2. paraméter a rememberme)
        $this->_registerSuccessLogin($data, false);

        return true;
    }    

    /**
     * Deletes the (invalid) remember-cookie to prevent infinitive login loops
     */
    public function deleteCookie()
    {
        // set the rememberme-cookie to ten years ago (3600sec * 365 days * 10).
        // that's obviously the best practice to kill a cookie via php
        // @see http://stackoverflow.com/a/686166/1114320
        //setcookie('rememberme', false, time() - (3600 * 3650));
        setcookie('rememberme', false, time() - (3600 * 3650), '/');
    }


    /**
     * Felhasználó kijelentkezés
     */
    public function logout()
    {
        // set the remember-me-cookie to ten years ago (3600sec * 365 days * 10).
        // that's obviously the best practice to kill a cookie via php
        // @see http://stackoverflow.com/a/686166/1114320
        
        // setcookie('rememberme', false, time() - (3600 * 3650));
        // setcookie('rememberme', false, time() - (3600 * 3650), '/');
        
        // delete the session
        Session::destroy();
    }

    /**
     * Visszadja annak a felhasználónak az adatait, ahol a felhasználói név vagy az email-cím egyezik a paraméterban kapott adattal
     *
     * @param string $username_or_email
     * @return object || false
     */
    public function getUserData($username_or_email)
    {
        // ha a felület admin, akkor csak azt a usert adja vissza aki admin
        $role_sql = (self::$area == 'admin') ? " AND `provider_type` = 'admin'" : '';
        
        $data = array(':username_or_email' => $username_or_email);
        $sql = "SELECT * FROM `{$this->table_name}` WHERE (`{$this->username_colname}` = :username_or_email OR `{$this->email_colname}` = :username_or_email)" . $role_sql;
        $sth = $this->connect->prepare($sql);
        $sth->execute($data);
        return $sth->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Visszadja a megadott id-vel rendelkező felhasználó adatait
     * 2. paraméter megadásakor a rememberme token egyezést is figyelembe veszi a felhasználó visszaadásakor 
     *
     * @param string $id
     * @param string $rememberme_token
     * @return object || false
     */
    public function getUserDataById($id, $rememberme_token = null)
    {
        // ha a felület admin, akkor csak azt a usert adja vissza aki admin
        $role_sql = (self::$area == 'admin') ? " AND `provider_type` = 'admin'" : '';                                

        $data = array(':id' => $id);

        $rememberme_sql = '';
        if (!is_null($rememberme_token)) {
            $rememberme_sql .= " AND `rememberme_token` = :rememberme_token AND `rememberme_token` IS NOT NULL";
            $data[':rememberme_token'] = $rememberme_token;
        }

        $sql = "SELECT * FROM `{$this->table_name}` WHERE `id` = :id" . $rememberme_sql . $role_sql;
        $sth = $this->connect->prepare($sql);
        $sth->execute($data);
        return $sth->fetch(PDO::FETCH_OBJ);    
    }  

    /**
     * Hibákat tartalmazó tömböt adja vissza
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Hiba hozzáadása
     * @param string $error
     */
    public function setError($error)
    {
        $this->error[] = $error;
    }

    /**
     * Ellenőrzi, hogy  üres-e a hibákat tartalmazó tömb
     */
    public function checkError()
    {
        return empty($this->error);
    }

    /**
     * Jelszó kompatibilitás library betöltése
     *
     * @access private
     */
    private function _loadPasswordCompatibility()
    {
        if (version_compare(PHP_VERSION, '5.5.0', '<')) {
            // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
            // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
            require_once(LIBS . '/password_compatibility_library.php');
        }
    }

    /**
     * Ellenőrzi, hogy aktív-e a felhasználó
     */
    private function _isActive($user_active)
    {
        if ($user_active != 1) {
            //$this->setError('Az ön belépési engedélye fel van függesztve!');
            return false;
        }
        return true;
    }
 
    /**
     * Sikeres bejelentkezésrögzítése
     * Utolsó sikertelen bejelentkezés idejének NULL-ra állítása
     * Bejelentkezés idejének beállítása
     * Ha a $rememberme értéke true, hozzáadja az sql parancshoz a rememberme_token mező update-jét is
     *
     * @param array $data 
     * @param bool $rememberme
     * @return void
     */
    private function _registerSuccessLogin($data, $rememberme = false)
    {
        $rememberme_sql = ($rememberme && array_key_exists(':rememberme_token', $data)) ? ", `rememberme_token` = :rememberme_token" : "";

        $sql = "UPDATE `{$this->table_name}` SET `failed_logins` = 0, `last_failed_login` = NULL, last_login_timestamp = :last_login_timestamp" . $rememberme_sql . " WHERE `id` = :id";
        $sth = $this->connect->prepare($sql);
        $sth->execute($data);
    }

    /**
     * Sikertelen bejelentkezés idejének rögzítése az adatbázisba, és a failed_logins mező értékének növelése 1-el 
     *
     * @param string $username_or_email
     * @return void
     */
    private function _registerFailedLogin($username_or_email)
    {
        $sql = "UPDATE `{$this->table_name}` SET `failed_logins` = failed_logins+1, `last_failed_login` = :last_failed_login WHERE `{$this->username_colname}` = :username_or_email OR '{$this->email_colname}' = :username_or_email";
        $sth = $this->connect->prepare($sql);
        $sth->execute(array( ':username_or_email' => $username_or_email, ':last_failed_login' => time() ));
    }

    /**
     * Jelszó ellenőrzése
     * @param string $password
     * @param string $hash
     * @return bool
     */
    private function _verifyPassword($password, $hash)
    {
        // jelszó kompatibilitás library betöltése régebbi php verzió esetén
        $this->_loadPasswordCompatibility(); 

        if (!password_verify($password, $hash)) {
            $this->setError('password_wrong');
            return false;
        }
        return true;
    }

    /**
     * Static call forwarder
     *
     * @param   string  $func  method name
     * @param   array   $args  passed arguments
     * @return  mixed
     * @throws  \BadMethodCallException
     */
/*
    public static function __callStatic($func, $args)
    {
        $instance = self::instance();

        if (method_exists($instance, $func))
        {
            return call_user_func_array(array($instance, $func), $args);
        }

        throw new \BadMethodCallException('Call to undefined method: '.get_called_class().'::'.$func);
    }    
*/

// ---- ACL -------------

    /**
     * Felhasználó engedélyének ellenőrzése a megadott művlethez 
     * 
     * @param string $permission    egy művelet neve pl.: delete_user 
     * @param string $target_url    egy átirányítási hely, ha nincs engedély 
     * @return void
     */
    public static function hasAccess($permission, $target_url = false)
    {
        //$instance = self::instance();   
        $instance = DI::get('auth');

            // !!!! ha nincs a permissions táblában a megadott permission, akkor automatikusan engedélyezve lesz
            /*
            if (!$instance->checkPermission($permission)) {
               return true;
            }
            */

        // ha még nincsenek lekérdezve a felhasználó permission-jai
        if (is_null($instance->permissions)) {
            
            if (Auth::isUserLoggedIn()) {
                $role_id = $instance->getRoleId();
                $instance->permissions = ($role_id == 1) ? '*' : $instance->getRolePerms($role_id);
                //$instance->permissions = $instance->getRolePerms($role_id);
            } else {
                $instance->permissions = array();
            }
        }

    // ha van engedély
        if($instance->_checkAccess($permission)){
            return true;
        }
    // ha nincs engedély
        else {
            if ($target_url === false) {
                return false;
            }
            if(!is_null($target_url)) {
                $instance->_accessDenied($permission, $target_url);
            }
            // ha null a target url, például ha a http referer értéke null
            else {
                $target_url = BASE_URL;
                if (AREA == 'admin') {
                    $target_url .= '/admin'; 
                }
                $instance->_accessDenied($permission, $target_url);
            }
        }
    } 

    /**
     * Engedélyek mentése
     *
     * @param integer $role_id
     * @param array $permission_data - engedély_id => '1', engedély_id => '0'... 
     */
    public function savePerms($role_id, $permissions_data)
    {
        // lekérdezzük a role_perms tábla megadott szerephez tartozó permission id-it és berakjuk egy tömbbe
        $role_perms_arr = array();    
        $sql = "SELECT * FROM `role_perm` WHERE `role_id` = {$role_id}";
        $sth = $this->connect->query($sql);
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $role_perms_arr[] = $row['perm_id'];
        }

        foreach ($permissions_data as $perm_id => $value)
        {
            $privilege = ($value == '1' || $value == 'on') ? 1 : 0;

            // megnézzük, hogy role-hoz engedélyezve van-e már a permission
            if (!in_array($perm_id, $role_perms_arr) && $privilege == 1) {
                $sql_insert = "INSERT INTO `role_perm` (`role_id`, `perm_id`) VALUES ({$role_id}, {$perm_id})";
                $this->connect->query($sql_insert);
            }
            elseif (in_array($perm_id, $role_perms_arr) && $privilege == 0) {
                $sql_delete = "DELETE FROM `role_perm` WHERE `role_id` = {$role_id} AND `perm_id` = {$perm_id}";
                $this->connect->query($sql_delete);
            }
        }
    }

    /**
     * Visszaadja a megadott role_id-jű csoport mindent adatát (paraméter nélkül minden adatot) a roles táblából 
     * @param integer $id
     * @return array
     */
    public function getRoles($id = null)
    {
        $where = "";
        if (!is_null($id)) {
            $id = (int)$id;
            $where .= " WHERE `id` = {$id}"; 
        }    
        $sth = $this->connect->query("SELECT * FROM `roles`" . $where);
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return (count($result) == 1) ? $result[0] : $result;
    }

    /**
     * Visszaadja a paraméterben megadott felhasználói csoport engedélyezett permission kulcsait 
     * @param integer $role_id
     * @return array
     */
    public function getRolePerms($role_id)
    {
        $role_id = (int)$role_id;    
        $sql = "SELECT `permissions`.`key` FROM `role_perm`
                JOIN `permissions` ON `role_perm`.`perm_id` = `permissions`.`id`
                WHERE `role_perm`.`role_id` = {$role_id}
                ORDER BY `permissions`.`key`
                ";
        
        $sth = $this->connect->query($sql);

        $permissions = array();
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $permissions[] = $row['key'];
        }
        return $permissions;
    }

    /**
     * Visszaadja az összes permissions-t (`id`, `key`, `desc` oszlop tartalmát)
     * @return array
     */
    public function getAllPerms()
    {
        $sth = $this->connect->query("SELECT `id`, `key`, `desc` FROM `permissions` ORDER BY `key`");
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Megvizsgálja, hogy létezik-e a megadott permission a permissions táblában
     * @return bool
     */
    public function checkPermission($permission)
    {
        $sth = $this->connect->prepare("SELECT `id` FROM `permissions` WHERE `key` = :permission_key");
        $sth->execute(array(":permission_key" => $permission));
        return ($sth->fetch(PDO::FETCH_ASSOC) === false) ? false : true;
    }

    /**
     * Megvizsgálja, hogy a permissions tömben szerepel e a megadott permission száma
     * @param integer $permission
     * @return bool
     */
    private function _checkAccess($permission)
    {
            /*
            $sql = "SELECT `id` FROM `permissions` WHERE `key` = :perm_key";    
            $sth = $this->connect->prepare($sql);
            $sth->execute(array(':perm_key' => $permission));
            $result = $sth->fetch(PDO::FETCH_ASSOC);
            if (!$result) {
                return false;
            }
            */

        if ($this->permissions === '*') {
            return true;
        } else {
            return in_array($permission, $this->permissions);
        }
    }

    /**
     * Ha nincs engedélye a felhasználónak, és átirányítás is meg van adva
     *
     * @param string $permission
     * @param string $target_url
     */
    private function _accessDenied($permission, $target_url)
    {
            /* Egyszerű verzió lekérdezés nélkül
            Message::set('error', 'Nincs engedélye a művelet végrehajtásához!');
            header('location: ' . $target_url);
            exit;
            */
 
        $sql = "SELECT `message` FROM `permissions` WHERE `key` = :perm_key";
        $sth = $this->connect->prepare($sql);
        $sth->execute(array(":perm_key" => $permission));
        $result = $sth->fetch(PDO::FETCH_ASSOC);

        if($result){
            $result['message'] = ($result['message'] === '') ? 'A művelet nem engedélyezett!' : $result['message'];
            Message::set('error', $result['message']);
        } else {
            //Message::set('error', 'Hozzáférés megtagadva!');
            throw new \Exception('Nincs ilyen muvelet az adatbazisban!');
            exit;
        }

        //$target_url = str_replace(BASE_URL, '', $target_url);
        header('location: ' . $target_url);
        exit;
    }

} //osztály vége
?>