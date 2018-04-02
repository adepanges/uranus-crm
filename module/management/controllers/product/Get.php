<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Get extends Management_Controller {

	public function index()
	{
        $this->_restrict_access('management_product_list', 'rest');
        $this->load->model('product_model');

        $this->product_model->set_datatable_param($this->_datatable_param());
        $data = $this->product_model->get_datatable();

        $this->_response_json([
            'recordsFiltered' => $data['total'],
            'data' => $data['row']
        ]);
	}

    public function byid($id = 0)
    {
        $this->_restrict_access('management_product_list', 'rest');
        $data = (object) [];
        $id = (int) $id;

        if($id)
        {
            $this->load->model('product_model');
            $data = $this->product_model->get_byid($id);
        }
        $this->_response_json($data);
    }
}
