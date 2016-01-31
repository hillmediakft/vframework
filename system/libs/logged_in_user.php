<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Logged_in_user  {

    private $roles;

    public function __construct()
    {
        $this->connect = db::get_connect();
        // $this->query = new Query($this->connect);
        $this->user_id = Session::get('user_id');
        $this->user_name = Session::get('user_name');
        $this->user_email = Session::get('user_email');
        $this->user_role_id = Session::get('user_role_id');
        $this->initRoles();
    }

    // populate roles with their associated permissions
    protected function initRoles()
    {
        $this->roles = array();
        $sql = "SELECT users.user_role_id, roles.role_name FROM users
                JOIN roles ON users.user_role_id = roles.role_id
                WHERE users.user_id = :user_id";
        $sth = $this->connect->prepare($sql);
        $sth->execute(array(":user_id" => $this->user_id));

        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $this->roles[$row["role_name"]] = Acl::create($row["user_role_id"]);
        }
    }

    // check if user has a specific privilege
    public function hasAccess($perm)
    {
        foreach ($this->roles as $role) {
            if ($role->hasPerm($perm)) {
                return true;
            }
        }
        return false;
    }

}