<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends Logistik_Controller {

    public function index()
    {
        // $this->_restrict_access('logistik_packing_notyet');
        $this->load->model('inventory_model');

        $this->_set_data([
            'title' => 'Stok Barang',
            'stock_product' => $this->inventory_model->get_product_stock($this->franchise->franchise_id)->result()
        ]);

        $this->blade->view('inc/logistik/inventory/app', $this->data);
    }
}
