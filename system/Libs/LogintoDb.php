<?php
namespace System\Libs;
use System\Libs\DI;
use System\Libs\Query;
use System\Libs\Session;

/**
 * Az adatbázis logs táblájába létrehoz egy új rekordot
 */
class LogIntoDb {

    private $connect;
    private $query;

    function __construct() {
        $this->connect = DI::get('connect');
        $this->query = new Query($this->connect);
    }

    /**
     * Új rekord létrehozása a logs táblában
     */
    public function index($type, $message)
    {
        /*
          $sth = $this->connect->prepare('INSERT INTO `logs` (user_id, message, date) VALUES(:user_id, :message, :date)');
          $sth->execute(array(
          ':user_id' => Session::get('user_data.id'),
          ':message' => '<span class="badge badge-sm">' . $type . '</span> ' . $message,
          ':date' => date("Y-m-d h:i:s")
          ));
         */

        $data = array(
            'user_id' => Session::get('user_data.id'),
            'action' => $type,
            'message' => $message,
            'date' => date("Y-m-d H:i:s")
        );
        $this->query->set_table(array('logs'));
        $this->query->insert($data);
    }

    /**
     * Új rekordok létrehozása a logs táblában
     */
    public function addLog($type, $message, $id_arr)
    {
        $user_id = Session::get('user_data.id');

        foreach ($id_arr as $id) {
            $data = array(
                'user_id' => $user_id,
                'action' => $type,
                'message' => '#' . $id . ' - ' . $message,
                'date' => date("Y-m-d H:i:s")
            );
            $this->query->set_table(array('logs'));
            $this->query->insert($data);
        } 
    }

    /**
     * Új rekordok létrehozása a logs táblában
     */
    public function changePropertyStatus($type, $message, $id_arr)
    {
        $user_id = Session::get('user_data.id');

        foreach ($id_arr as $id) {
            $data = array(
                'user_id' => $user_id,
                'action' => $type,
                'message' => '#' . $id . ' - ' . $message,
                'date' => date("Y-m-d H:i:s")
            );
            $this->query->set_table(array('logs'));
            $this->query->insert($data);
        } 
    }

}
?>