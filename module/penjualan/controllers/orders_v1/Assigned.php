<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assigned extends Penjualan_Controller {

    public function index()
    {
        $this->_restrict_access('penjualan_orders_assigned');
        $this->session->set_userdata('orders_state', 'orders_v1/assigned');

        $this->_set_data([
            'title' => 'Assigned Orders'
        ]);

        $this->blade->view('inc/penjualan/orders/assigned_v1', $this->data);
    }
}
