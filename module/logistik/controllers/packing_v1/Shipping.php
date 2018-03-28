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

    public function update_shipping()
    {
        // $this->_restrict_access('logistik_packing_action_shipping','rest');

        $id = (int) $this->input->post('order_id');
        $logistic_id = (int) $this->input->post('logistic_id');
        $shipping_code = $this->input->post('shipping_code');

        $this->load->model('orders_model');
        $res = $this->orders_model->get_byid_v1($id);
        $data = $res->first_row();
        $profile = $this->session->userdata('profile');

        $order_status = [
            'logistic_id' => $logistic_id,
            'shipping_code' => $shipping_code
        ];

        $res1 = $this->orders_model->upd($id, $order_status);

        if($res1)
        {
            $this->_response_json([
                'status' => 1,
                'message' => 'Berhasil mengubah data'
            ]);
        }
        else
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Gagal mengubah data'
            ]);
        }
    }
}
