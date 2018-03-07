<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pickup extends Logistik_Controller {

    public function index()
    {
        $this->_restrict_access('logistik_packing_alredy');
        $this->session->set_userdata('packing_state', 'packing_v1/pickup');
        $this->load->model('master_model');
        $this->_set_data([
            'title' => 'Pesanan Sudah di Packing',
            'master_logistics' => $this->master_model->logistics()->result(),
            'packing_state' => 'packing_v1/pickup'
        ]);

        $this->blade->view('inc/logistik/packing/pickup_v1', $this->data);
    }

    public function shipping()
    {
        $this->_restrict_access('logistik_packing_action_shipping','rest');

        $id = (int) $this->input->post('order_id');
        $logistic_id = (int) $this->input->post('logistic_id');
        $shipping_code = $this->input->post('shipping_code');

        $this->load->model(['orders_model','master_model','logistics_process_model']);
        $res = $this->orders_model->get_byid_v1($id);
        $data = $res->first_row();
        $profile = $this->session->userdata('profile');

        if(
            !$res->num_rows() ||
            $data->order_status_id != 8 ||
            $data->logistics_status_id != 4
        ) $this->_response_json([
            'status' => 0,
            'message' => 'Tidak dapat mengubah status'
        ]);

        $logistics_status = $this->master_model->logistics_status(5)->first_row();

        $label_logistics_status = isset($logistics_status->label)?$logistics_status->label:'Pengiriman';

        $order_status = [
            'logistic_id' => $logistic_id,
            'logistics_status_id' => 5,
            'logistics_status' => $label_logistics_status,
            'shipping_code' => $shipping_code
        ];

        $logistik_process = [
            'order_id' => $id,
            'user_id' => $profile['user_id'],
            'logistics_status_id' => 5,
            'status' => $label_logistics_status,
            'notes' => "Pesanan dalam proses pengiriman oleh ekspedisi dengan No. Resi <b>$shipping_code</b>",
            'created_at' => date('Y-m-d H:i:s')
        ];
        $res1 = $this->orders_model->upd($id, $order_status);
        $res2 = $this->logistics_process_model->add($logistik_process);

        if($res1 && $res2)
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
