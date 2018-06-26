<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Provinsi extends API_Controller {

    function __construct()
    {
        parent::__construct();
    }

    public function list()
    {
        $key = 'master_provinsi_list';
        $data = $this->cache->get($key);
        $source = 'cache';
        if($data === FALSE)
        {
            $this->load->model('master_wilayah_model');
            $data = $this->master_wilayah_model->provinsi()->result();
            $source = 'database';
            $this->cache->save($key, $data, 3600);
        }

        $this->_response_json([
            'data' => $data,
            'source' => $source
        ]);
    }
}
