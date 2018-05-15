<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Del extends Keuangan_Controller {
    public function index($id = 0)
    {
        $this->_restrict_access('management_product_del', 'rest');

        $account_statement_id = (int) $id;
        if(!$account_statement_id) $this->_response_json([
            'status' => 0,
            'message' => 'id must be set in uri'
        ]);

        $this->load->model('account_statement_model');

        if($this->account_statement_model->del($account_statement_id))
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
