<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Uranus_Controller extends CI_Controller {
    protected $data = ['b' => 2];

    function __construct()
    {
        parent::__construct();
        $this->load->helper('uranus');
    }

    protected function _set_data($data)
    {
        if(!empty($data) && is_array($data))
        {
            $this->data = array_merge($this->data, $data);
        }
    }
}
