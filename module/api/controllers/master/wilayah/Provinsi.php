<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Provinsi extends API_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('master_wilayah_model');
    }

    public function list()
    {
        $this->_response_json([
            'data' => $this->master_wilayah_model->provinsi()->result()
        ]);
    }
}
