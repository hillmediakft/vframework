<?php

class Acl
{
    private $permissions;
	private $roles;
	
    private function __construct() {
        $this->user_id = Session::get('user_id');
		$this->connect = db::get_connect();
		$this->permissions = array();
		$this->roles = array();
	}
	
	/**
	 * Statikusan meghívható metódus: ha nincs még az Acl osztály példányosítva, 
	 * akkor létrehozza az objektumot
	 * @return 	az Acl objektumot adja vissza
	 */
	public static function Create()
    {
        static $_instance = null;
        if ($_instance === null) {
            $_instance = new Acl();
        }
        return $_instance;
    }
 
    // a felhasználói szerephez (csoport, pl. admin) tartozó jogosultságot adja vissza tömbben
    public function getRolePerms($role_id) {
		$sql = "SELECT permissions.perm_key FROM role_perm
                JOIN permissions ON role_perm.perm_id = permissions.perm_id
                WHERE role_perm.role_id = :role_id";
        $sth = $this->connect->prepare($sql);
        $sth->execute(array(":role_id" => $role_id));
 
        while($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $this->permissions[$row["perm_key"]] = true;
        }
	}
 
    // a bejelentkezett felhasználóhoz tartozó szerepe(ke)t (lehetne több is) adja vissza 
    public function getUserRoles() {
        $sql = "SELECT users.user_role_id, roles.role_name FROM users
                JOIN roles ON users.user_role_id = roles.role_id
                WHERE users.user_id = :user_id";
        $sth = $this->connect->prepare($sql);
        $sth->execute(array(":user_id" => $this->user_id));
 
        while($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $this->roles[$row["role_name"]] = $this->getRolePerms($row["user_role_id"]);
        }
    }

    // check if a permission is set
    public function hasPerm($permission) {
        return isset($this->permissions[$permission]);
    }
	
	// check if user has a specific privilege
    public function userHasAccess($perm) {
		$this->getUserRoles();
		foreach ($this->roles as $role) {
            if ($this->hasPerm($perm)) {
                return true;
            }
        }
        return false;
    }
}

?>