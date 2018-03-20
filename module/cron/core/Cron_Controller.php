<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(FCPATH.'resources/core/Dermeva_Controller.php');

class Cron_Controller extends Dermeva_Controller {
    function __construct()
    {
        parent::__construct();
        if(!$this->input->is_cli_request())
        {
            echo 'Not allowed';
            exit;
        }

        $this->load->library('eins_log');
    }
}
