<?php
namespace System\Admin\Model;
use System\Core\AdminModel;

class Logs_model extends AdminModel {

    protected $table = 'logs';

    function __construct()
    {
        parent::__construct();
    }

    /**
     *  Felhasználók adatainak lekérdezése
     *
     *  @param  string|integer    $user_id (csak ennek a felhasználónak az adatait adja vissza
     *  @return array|false
     */
    public function get_logs($user_id = null)
    {
        $this->query->set_join('left', 'users', 'users.id', '=', 'logs.user_id');
        if(!is_null($user_id)){
            $this->query->set_where('user_id', '=', $user_id);
        }
        return $this->query->select();
    }
}
?>