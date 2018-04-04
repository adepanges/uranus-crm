<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Double extends Penjualan_Controller {

    public function index()
    {
        $this->_restrict_access('penjualan_orders_confirm_buy');
        $this->session->set_userdata('orders_state', 'orders_v1/double');
        $this->_set_data([
            'title' => 'Double Orders'
        ]);

        $this->blade->view('inc/penjualan/orders/double_v1', $this->data);
    }

    public function get()
	{
        // $this->_restrict_access('management_cs_team_list', 'rest');
        $this->load->model('double_orders_model');

        $this->double_orders_model->set_datatable_param($this->_datatable_param());
        $double_orders = $this->double_orders_model->get_datatable();

        $this->_response_json([
            'recordsFiltered' => $double_orders['total'],
            'data' => $double_orders['row']
        ]);
	}
}
