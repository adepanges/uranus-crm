<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Desa extends API_Controller {

    function __construct()
    {
        parent::__construct();
    }

    public function list($kecamatan_id = 0)
	{
        $key = 'master_desa_list_'.$kecamatan_id;
        $data = $this->cache->get($key);
        $source = 'cache';
        if($data === FALSE)
        {
            $this->load->model('master_wilayah_model');
            $data = $this->master_wilayah_model->desa($kecamatan_id)->result();
            $source = 'database';
            $this->cache->save($key, $data, 3600);
        }

        $this->_response_json([
            'data' => $data,
            'source' => $source
        ]);
	}
}
