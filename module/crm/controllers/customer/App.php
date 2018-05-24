<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends Management_Controller {

	public function index()
	{
        $this->_restrict_access('crm_customer');
        $this->_set_data([
            'title' => 'Customer'
        ]);

        $this->blade->view('inc/crm/customer/app', $this->data);
	}

    public function save()
    {
        $customer_id = (int) $this->input->post('customer_id');
        if($customer_id) $this->_restrict_access('crm_customer_upd', 'rest');
        else $this->_restrict_access('crm_customer_add', 'rest');

        $data = [
            'full_name' => clean_special_char($this->input->post('full_name')),
            'email' => $this->input->post('email'),
            'gender' => $this->input->post('gender'),
            'birthdate' => $this->input->post('birthdate'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->load->model('customer_model');
        if(!$customer_id)
        {
            // tambah
            $data['created_at'] = date('Y-m-d H:i:s');
            $res = $this->customer_model->add($data);
        }
        else
        {
            // ubah
            $res = $this->customer_model->upd($data, $customer_id);
        }

        if($res)
        {
            $this->_response_json([
                'status' => 1,
                'message' => 'Berhasil menyimpan data'
            ]);
        }
        else
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Gagal menyimpan data'
            ]);
        }
    }
}
