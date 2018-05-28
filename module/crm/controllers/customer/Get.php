<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Get extends Management_Controller {

	public function index()
	{
        $this->_restrict_access('crm_customer_list', 'rest');
        $this->load->model('customer_model');

        $this->customer_model->set_datatable_param($this->_datatable_param());
        $data = $this->customer_model->get_datatable();

        $this->_response_json([
            'recordsFiltered' => $data['total'],
            'data' => $data['row']
        ]);
	}

    public function byid($id = 0)
    {
        $this->_restrict_access('crm_customer_list', 'rest');
        $data = (object) [];
        $id = (int) $id;

        if($id)
        {
            $this->load->model('customer_model');
            $data = $this->customer_model->get_byid($id);
        }
        $this->_response_json($data);
    }

    public function phone_byid($customer_phonenumber_id = 0)
    {
        $this->_restrict_access('crm_customer_list', 'rest');
        $data = (object) [];
        $customer_phonenumber_id = (int) $customer_phonenumber_id;

        if($customer_phonenumber_id)
        {
            $this->load->model('customer_phonenumber_model');
            $data = $this->customer_phonenumber_model->get_byid($customer_phonenumber_id);
        }
        $this->_response_json($data);
    }
}
