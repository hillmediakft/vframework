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
     *  Napló adatok lekérdezése
     *
     *  @param  integer    $user_id (csak ennek a felhasználóhoz tartozó napló bejegyzéseket adja visssz
     *  @return array|false
     */
    public function findLogs($user_id = null)
    {
        if(!is_null($user_id)){
            $this->query->set_join('left', 'users', 'users.id', '=', 'logs.user_id');
            $this->query->set_where('logs.user_id', '=', $user_id);
        }
        return $this->query->select();
    }
}
?>