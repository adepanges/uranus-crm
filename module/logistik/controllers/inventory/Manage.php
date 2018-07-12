<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage extends Logistik_Controller {

    public function index()
    {
        redirect('inventory');
    }

    public function product($product_id = 0)
    {
        // $this->_restrict_access('logistik_packing_notyet');
        $this->load->model('inventory_model');
        $data = $this->inventory_model->get_product($this->franchise->franchise_id, $product_id);

        if(empty($data)) redirect('inventory');

        $this->_set_data([
            'title' => 'Manage Stok Barang',
            'product' => $data
        ]);

        $this->blade->view('inc/logistik/inventory/manage', $this->data);
    }

    public function get()
    {
        $this->load->model('inventory_model');

        $params = [
            'franchise_id' => $this->franchise->franchise_id,
            'product_id' => (int) $this->input->post('product_id')
        ];

        $this->inventory_model->set_datatable_param($this->_datatable_param());
        $data = $this->inventory_model->get_datatable($params);

        $this->_response_json([
            'recordsFiltered' => $data['total'],
            'data' => $data['row']
        ]);
    }
}
