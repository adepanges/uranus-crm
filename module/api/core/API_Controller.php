<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class API_Controller extends CI_Controller {
    function __construct()
    {
        parent::__construct();
        $this->load->helper('uranus');
        $this->load->driver('cache', array('adapter' => 'file', 'key_prefix' => 'api_'));
    }

    protected function _response_json($resp)
    {
        if(is_object($resp)) $resp->system_process_time = (microtime(true) - URANUS_LAUNCH);
        else if(is_array($resp)) $resp['system_process_time'] = (microtime(true) - URANUS_LAUNCH);

        header('Content-Type: application/json');
        echo json_encode($resp);

        exit;
    }
}
