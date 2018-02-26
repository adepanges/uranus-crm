<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends Penjualan_Controller {

    public function index()
    {
        $this->_restrict_access('penjualan_orders');
        $this->_set_data([
            'title' => 'Pesanan'
        ]);

        $this->blade->view('inc/penjualan/orders/app_v1', $this->data);
    }
}
