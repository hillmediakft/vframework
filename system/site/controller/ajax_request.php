<?php
namespace System\Site\Controller;

use System\Core\Site_controller;

class Ajax_request extends Site_controller {

    function __construct()
    {
        parent::__construct();
        //$this->loadModel('ajax_request_model');
    }

    public function index()
    {
        $this->response->redirect('error');
    }
  

}
?>