<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shipping extends Logistik_Controller {

    public function index()
    {
        $this->_restrict_access('logistik_packing_alredy');
        $this->session->set_userdata('packing_state', 'packing_v1/shipping');
        $this->load->model('master_model');
        $this->_set_data([
            'title' => 'Pesanan Sudah di Packing',
            'packing_state' => 'packing_v1/shipping'
        ]);

        $this->blade->view('inc/logistik/packing/shipping_v1', $this->data);
    }
}
