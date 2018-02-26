<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Get extends Penjualan_Controller {

    public function index()
    {
        $this->_restrict_access('penjualan_orders_list');
        $this->load->model('orders_model');

        $this->orders_model->set_datatable_param($this->_datatable_param());
        $data = $this->orders_model->get_datatable_v1();

        $this->_response_json([
            'recordsFiltered' => $data['total'],
            'data' => $data['row']
        ]);
    }
}
