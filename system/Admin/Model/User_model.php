<?php
namespace System\Admin\Model;
use System\Core\AdminModel;
use System\Libs\Config;

class User_model extends AdminModel {

    // tábla neve
    protected $table = 'users';
    // id neve
    //protected $id = 'id';

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
     *  @param  string|integer    $id (csak ennek a felhasználónak az adatait adja vissza)
     *  @return array|false
     */
    public function selectUser($id = null)
    {
        $this->query->set_columns(array(
            'users.id',
            'users.name',
            'users.first_name',
            'users.last_name',
            'users.active',
            'users.email',
            'users.role_id',
            'users.phone',
            'users.photo',
            'roles.role'
        ));
        $this->query->set_join('left', 'roles', 'users.role_id', '=', 'roles.id');
        
        if(!is_null($id)){
            $this->query->set_where('users.id', '=', $id);
// $this->query->debug();            
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
        $sth = $this->connect->prepare("SELECT COUNT(*) FROM `users` WHERE `name` = :name");
        $sth->execute(array(':name' => $username));
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
        $this->query->set_columns(array('id'));
        $this->query->set_where('name', '=', $username);
        //itt megadjuk, hogy nem vonatkozik a bejelentkezett user-re (mert ha nem módosítja a nevet akkor már van ilyen user név)
        $this->query->set_where('id', '!=', $id);
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
    public function checkEmail($email)
    {
        $sth = $this->connect->prepare("SELECT COUNT(*) FROM `users` WHERE `email` = :email");
        $sth->execute(array(':email' => $email));
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
        $this->query->set_columns(array('email'));
        $this->query->set_where('email', '=', $email);
        //itt megadjuk, hogy nem vonatkozik a bejelentkezett user-re (mert ha nem módosítja a nevet akkor már van ilyen user név)
        $this->query->set_where('id', '!=', $id);
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
        $this->query->set_where('id', '=', $id);
        return $this->query->update($data);        
    }

    /**
     * DELETE
     */
    public function delete($id)
    {
        return $this->query->delete('id', '=', $id);        
    }

    /**
     * User fotójának lekérdezése
     */
    public function selectPicture($id)
    {
        $this->query->set_columns(array('photo'));
        $this->query->set_where('id', '=', $id);
        $result = $this->query->select();
        return $result[0]['photo'];
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
        $this->query->set_where('id', '=', $id);
        return $this->query->update(array('active' => $data));
    }

    /**
     * Megviszgálja, hogy az adott id-jű user szuperadmin-e 
     *
     * @return true ha superadmin, false ha nem
     */
    public function is_user_superadmin($id)
    {
        $this->query->set_columns(array('role_id'));
        $this->query->set_where('id', '=', $id);
        $result = $this->query->select();
        return ($result[0]['role_id'] == '1') ? true : false;
    }

    /**
     * 	Visszaadja a userss tábla user_role_id oszlop tartalmát
     * 	A felhasználói szerepek számának meghatározásához kell
     */
    public function rolesCounter()
    {
        $this->query->set_columns('role_id');
        return $this->query->select();
    }

    /*
     * Lekérdezzük egy bizonyos e-mail címmel rendelkező user nevét és password-ját (elfelejtett jelszó esetén)
     *
     *  @param  string  $email
     */
    public function getPasswordHash($email)
    {
        $this->query->set_columns(array('name', 'password_hash'));
        $this->query->set_where('email', '=', $email);
        return $this->query->select();
    }

    /*
     * Új jelszó adatbázisba írása (elfelejtett jelszó esetén)
     *
     * @param string $email
     * @param string $password_hash
     */
    public function setNewPassword($email, $password_hash)
    {
        $this->query->set_where('email', '=', $email);
        return $this->query->update(array('password_hash' => $password_hash));
    }    

} // end class
?>