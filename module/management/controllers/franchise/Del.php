<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Del extends Management_Controller {
    public function index($id = 0)
    {
        // $this->_restrict_access('management_product_del', 'rest');

        $franchise_id = (int) $id;
        if(!$franchise_id) $this->_response_json([
            'status' => 0,
            'message' => 'id must be set in uri'
        ]);

        $this->load->model('franchise_model');

        if($this->franchise_model->del($franchise_id))
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
