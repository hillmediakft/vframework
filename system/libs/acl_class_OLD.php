<?php

class Acl {

    public $permissions;
    private $roles;

    private function __construct($role_id) {
        $this->user_id = Session::get('user_id');
        $this->permissions = array();
        $this->roles = array();
        $this->connect = db::get_connect();
        $this->getRolePerms($role_id);
    }

    /**
     * Statikusan meghívható metódus: ha nincs még az Acl osztály példányosítva, 
     * akkor létrehozza az objektumot
     * @return 	az Acl objektumot adja vissza
     */
    public static function Create($role_id) {
        static $_instance = null;
        if ($_instance === null) {
            $_instance = new Acl($role_id);
        }
        return $_instance;
    }    
    
    /**
     * Statikusan meghívható metódus: ha nincs még az Acl osztály példányosítva, 
     * akkor létrehozza az objektumot
     * @return 	az Acl objektumot adja vissza
     */
    public function getRolePerms($role_id) {
        $sql = "SELECT permissions.perm_key FROM role_perm
                JOIN permissions ON role_perm.perm_id = permissions.perm_id
                WHERE role_perm.role_id = :role_id";
        $sth = $this->connect->prepare($sql);
        $sth->execute(array(":role_id" => $role_id));

        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {

            $this->permissions[$row["perm_key"]] = true;
        }
        return $this;

    }

    // check if a permission is set
    public function hasPerm($permission) {
        return isset($this->permissions[$permission]);
    }

}
?>