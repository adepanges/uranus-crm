<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kecamatan extends API_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('master_wilayah_model');
    }

    public function list($kabupaten_id = 0)
    {
        $this->_response_json([
            'data' => $this->master_wilayah_model->kecamatan($kabupaten_id)->result()
        ]);
    }
}
