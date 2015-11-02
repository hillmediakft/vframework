<?php
class Ajax_request extends Site_controller {

    function __construct() {
        parent::__construct();
        // Auth::handleExpire();
        $this->loadModel('ajax_request_model');
    }

    public function index() {
        Util::redirect('error');
    }
   
    
    /**
     *  AJAX Email küldés
     */
    public function ajax_send_email() {
        if (Util::is_ajax()) {

            $from_email = ;
            $from_name = ;
            $message = ;
            $to_email = ;
            $to_name = $to_email;
            $subject = '';
            
            $result = $this->ajax_request_model->send_email($from_email, $from_name, $subject, $message, $to_email, $to_name);

            if ($result) {
                $message = array(
                  'status' => 'success',
                  'message' => 'Pozitív üzenet!'
                );
                echo json_encode($message);
            } else {
                $message = array(
                  'status' => 'error',
                  'message' => 'Negatív üzenet!'
                );
                echo json_encode($message);
            }
        } else {
            Util::redirect('error');
        }
    }

}
?>