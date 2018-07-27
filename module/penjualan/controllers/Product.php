<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends Penjualan_Controller {

	public function get()
	{
        $this->load->model('product_model');

        $this->product_model->set_datatable_param($this->_datatable_param());
        $data = $this->product_model->get_datatable();

        $this->_response_json([
            'recordsFiltered' => $data['total'],
            'data' => $data['row']
        ]);
	}

    public function get_package()
	{
        $this->load->model('product_package_model');
        $this->product_package_model->set_datatable_param($this->_datatable_param());
        $data = $this->product_package_model->get_datatable();

        $this->_response_json([
            'recordsFiltered' => $data['total'],
            'data' => $data['row']
        ]);
	}
}
