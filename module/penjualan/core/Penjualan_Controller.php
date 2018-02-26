<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(FCPATH.'resources/core/Dermeva_Controller.php');

class Penjualan_Controller extends Dermeva_Controller {
    function __construct()
    {
        parent::__construct();

        if(!$this->_is_sso_signed())
        {
            redirect($this->config->item('sso_link').'/auth/log/out');
        }
    }
}
