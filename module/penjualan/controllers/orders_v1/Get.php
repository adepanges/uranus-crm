<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Get extends Penjualan_Controller {

    public function index($flag = 'new')
    {
        $this->_restrict_access('penjualan_orders_list');
        $this->load->model('orders_model');
        $params = [
            'order_status_id' => 1
        ];
        $status = [
            'new' => 1,
            'pending' => 3,
            'cancel' => 4,
            'confirm_buy' => 5
        ];

        if(isset($status[$flag])) $params['order_status_id'] = (int) $status[$flag];

        $this->orders_model->set_datatable_param($this->_datatable_param());
        $data = $this->orders_model->get_datatable_v1($params);

        $this->_response_json([
            'recordsFiltered' => $data['total'],
            'data' => $data['row']
        ]);
    }
}
