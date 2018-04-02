<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class API_Controller extends CI_Controller {
    function __construct()
    {
        parent::__construct();
        $this->load->helper('uranus');
    }

    protected function _response_json($resp)
    {
        header('Content-Type: application/json');
        echo json_encode($resp);
        exit;
    }
}
