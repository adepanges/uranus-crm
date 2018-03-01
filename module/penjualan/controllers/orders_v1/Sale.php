<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sale extends Penjualan_Controller {

    public function index()
    {
        $this->_restrict_access('penjualan_orders_sale');
        $this->session->set_userdata('orders_state', 'orders_v1/sale');
        $this->_set_data([
            'title' => 'Sale Orders'
        ]);

        $this->blade->view('inc/penjualan/orders/sale_v1', $this->data);
    }
}
