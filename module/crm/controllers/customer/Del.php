<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Del extends Management_Controller {
    public function index($id = 0)
    {
        $this->_restrict_access('crm_customer_del', 'rest');

        $customer_id = (int) $id;
        if(!$customer_id) $this->_response_json([
            'status' => 0,
            'message' => 'id must be set in uri'
        ]);

        $this->load->model('customer_model');

        if($this->customer_model->del($customer_id))
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

    public function phone($id = 0)
    {
        $this->_restrict_access('crm_customer_del', 'rest');

        $customer_phonenumber_id = (int) $id;
        if(!$customer_phonenumber_id) $this->_response_json([
            'status' => 0,
            'message' => 'id must be set in uri'
        ]);

        $this->load->model('customer_phonenumber_model');

        if($this->customer_phonenumber_model->del($customer_phonenumber_id))
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
