<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Get extends Penjualan_Controller {

    public function index($flag = 'new')
    {
        $this->_restrict_access('penjualan_orders_list');
        $this->load->model('orders_model');

        $params = [
            'order_status_id' => 1,
            'user_id' => $this->profile['user_id']
        ];
        $status = [
            'new' => 1,
            'pending' => 3,
            'cancel' => 4,
            'confirm_buy' => 5,
            'verify' => 6,
            'sale' => 7,
        ];
        $only_see_own = FALSE;

        if(isset($status[$flag])) $params['order_status_id'] = (int) $status[$flag];
        if(
            isset($this->role_active['role_id']) &&
            (
                (
                    // CS
                    $this->role_active['role_id'] == 5 &&
                    in_array($params['order_status_id'], [2,3,5,6,7])
                )
                ||
                (
                    // finance
                    $this->role_active['role_id'] == 3 &&
                    in_array($params['order_status_id'], [7])
                )
            )
        )
        {
            $only_see_own = TRUE;
        }

        $this->orders_model->set_datatable_param($this->_datatable_param());
        $data = $this->orders_model->get_datatable_v1($params, $only_see_own);

        $this->_response_json([
            'recordsFiltered' => $data['total'],
            'data' => $data['row']
        ]);
    }
}
