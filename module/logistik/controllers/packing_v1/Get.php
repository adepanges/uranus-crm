<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Get extends Logistik_Controller {

    public function index($flag = 'new')
    {
        // $this->_restrict_access('penjualan_orders_list');
        $this->load->model('orders_model');

        $params = [
            'order_status_id' => 7,
            'logistics_status_id' => 1
        ];
        $status = [
            'notyet' => 1,
            'pending' => 2,
            'alredy' => 3,
            'pickup' => 4,
            'shipping' => 5
        ];

        if(isset($status[$flag])) $params['logistics_status_id'] = (int) $status[$flag];
        if($params['logistics_status_id'] != 1) $params['order_status_id'] = 8;

        $this->orders_model->set_datatable_param($this->_datatable_param());
        $data = $this->orders_model->get_datatable_v1($params);

        $this->_response_json([
            'recordsFiltered' => $data['total'],
            'data' => $data['row']
        ]);
    }
}
