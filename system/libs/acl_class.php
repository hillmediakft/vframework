<?php 
class Acl {

    // DB kapcsolat objektum
    public $connect;

    // Ez a tömb tárolja a felhasználói csoportnak engedélyezett elemeket
    public $permissions = array();

    public function __construct()
    {
        $this->connect = db::get_connect();
        $role_id = Session::get('user_role_id');
        $this->_get_role_perms($role_id);
    }

    /**
     * Statikusan meghívható metódus: ha nincs még az Acl osztály példányosítva, 
     * akkor létrehozza az objektumot
     *
     * @param string $permission    egy művelet neve pl.: delete_user 
     * @param string $target_url    egy átirányítási hely, ha nincs engedély 
     * @return void
     */
    public static function check($permission, $target_url = null)
    {
        static $_instance = null;
        if ($_instance === null) {
            $_instance = new Acl();
            //return $_instance->_get_permissions();
        }

            if($_instance->_check_access($permission)){
                return true;
            }
            else {
                if(!is_null($target_url)) {
                    $_instance->_access_denied($permission, $target_url);
                }
                return false;
            }

        //return $_instance->_get_permissions();
        //return $_instance;
    } 

    /**
     * Lekérdezi, hogy egy bizonyos felhasználói csoport milyen engedélyekkel rendelkezik
     * (permissions tábla perm_key elemeiből adja vissza azokat, amelyek engedélyezve vannak a paraméterben kapott felhasználói csoportnak)
     * 
     * @param integer $role_id      felhasználói csoport role_id-je
     * @return 	
     */
    private function _get_role_perms($role_id)
    {
        $sql = "SELECT permissions.perm_key FROM role_perm
                JOIN permissions ON role_perm.perm_id = permissions.perm_id
                WHERE role_perm.role_id = :role_id";
        
        $sth = $this->connect->prepare($sql);
        $sth->execute(array(":role_id" => $role_id));

        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $this->permissions[$row["perm_key"]] = true;
            // $this->permissions[] = $row['perm_key'];
        }
    }

/*
    public function _get_permissions()
    {
        return $this->permissions;
    }
*/

    /**
     * Megvizsgálja, hogy van-e engedélye a felhasználónak a művelet végrehajtására
     */
    private function _check_access($permission)
    {
        return isset($this->permissions[$permission]);
        // return in_array($permission, $this->permissions);
    }

    /**
     * Ha nincs engedély, akkor lekérdezi az engedélyhez tartozó hibaüzenetet és átirányit a $target_url címre
     */
    private function _access_denied($permission, $target_url)
    {
        $sql = "SELECT perm_message FROM permissions WHERE perm_key = :perm";
        $sth = $this->connect->prepare($sql);
        $sth->execute(array(":perm" => $permission));
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $result = $row["perm_message"];
        }

        if(isset($result)){
            Message::set('error', $result);
        } else {
            throw new Exception('Nincs ilyen muvelet az adatbazisban!');
            exit;
        }
        //$target_url = str_replace(BASE_URL . "admin/", "", $target_url);
        Util::redirect($target_url);
    }

}
?>