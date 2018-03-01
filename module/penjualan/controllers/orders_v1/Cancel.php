<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cancel extends Penjualan_Controller {

    public function index()
    {
        $this->_restrict_access('penjualan_orders_cancel');
        $this->session->set_userdata('orders_state', 'orders_v1/cancel');
        $this->_set_data([
            'title' => 'Canceled Orders'
        ]);

        $this->blade->view('inc/penjualan/orders/cancel_v1', $this->data);
    }
}
