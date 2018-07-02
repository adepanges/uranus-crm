<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kabupaten extends API_Controller {

    function __construct()
    {
        parent::__construct();
    }

    public function list($provinsi_id = 0)
    {
        $key = 'master_kabupaten_list_'.$provinsi_id;
        $data = $this->cache->get($key);
        $source = 'cache';
        if($data === FALSE)
        {
            $this->load->model('master_wilayah_model');
            $data = $this->master_wilayah_model->kabupaten($provinsi_id)->result();
            $source = 'database';
            $this->cache->save($key, $data, 3600);
        }

        $this->_response_json([
            'data' => $data,
            'source' => $source
        ]);
    }
}
