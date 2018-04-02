<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wilayah extends Dermeva_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('master_model');
    }

	public function provinsi()
	{
        $this->_response_json([
            'data' => $this->master_model->wilayah_provinsi()->result()
        ]);
	}

    public function kabupaten($provinsi_id = 0)
	{
        $this->_response_json([
            'data' => $this->master_model->wilayah_kabupaten($provinsi_id)->result()
        ]);
	}

    public function kecamatan($kabupaten_id = 0)
	{
        $this->_response_json([
            'data' => $this->master_model->wilayah_kecamatan($kabupaten_id)->result()
        ]);
	}

    public function desa($kecamatan = 0)
	{
        $this->_response_json([
            'data' => $this->master_model->wilayah_desa($kecamatan)->result()
        ]);
	}

    function generate_cache()
    {

    }
}
