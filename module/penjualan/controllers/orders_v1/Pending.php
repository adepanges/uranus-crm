<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pending extends Penjualan_Controller {

    public function index()
    {
        $this->_restrict_access('penjualan_orders_pending');
        $this->session->set_userdata('orders_state', 'orders_v1/pending');
        $this->_set_data([
            'title' => 'Pending Orders'
        ]);

        $this->blade->view('inc/penjualan/orders/pending_v1', $this->data);
    }
}
