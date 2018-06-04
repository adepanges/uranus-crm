<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detail extends Management_Controller {

	public function index($id)
	{
        $id = (int) $id;
        $this->_restrict_access('crm_customer');
        $this->load->model('customer_model');

        $customer = $this->customer_model->get_byid($id);
        if(empty($customer)) redirect('customer');

        $this->_set_data([
            'title' => 'Customer Detail',
            'customer' => $customer,
            'phone_number' => $this->customer_model->get_phone_byid($id)
        ]);

        $this->blade->view('inc/crm/customer/detail', $this->data);
	}
}
