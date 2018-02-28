<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Confirm_buy extends Penjualan_Controller {

    public function index()
    {
        $this->_restrict_access('penjualan_orders_confirm_buy');
        $this->session->set_userdata('orders_state', 'orders_v1/confirm_buy');
        $this->_set_data([
            'title' => 'Orders Confirm Buy'
        ]);

        $this->blade->view('inc/penjualan/orders/confirm_buy_v1', $this->data);
    }
}
