<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(FCPATH.'resources/core/Dermeva_Controller.php');

class Management_Controller extends Dermeva_Controller {
    function __construct()
    {
        parent::__construct();

        if(!$this->_is_sso_signed())
        {
            redirect($this->config->item('sso_link').'/auth/log/out');
        }
    }

    protected function normaliza_name($string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        $string = strtolower(str_replace('-', '_', $string));
        return $string;
    }
}
