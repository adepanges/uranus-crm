<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Del extends Management_Controller {
    public function index($id = 0)
    {
        $this->_restrict_access('management_network_del', 'rest');

        $network_id = (int) $id;
        if(!$network_id) $this->_response_json([
            'status' => 0,
            'message' => 'id must be set in uri'
        ]);

        $this->load->model('network_model');

        if($this->network_model->del($network_id))
        {
            $this->_response_json([
                'status' => 1,
                'message' => 'Berhasil menghapus data'
            ]);
        }
        else
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Gagal menghapus data'
            ]);
        }
    }
}
