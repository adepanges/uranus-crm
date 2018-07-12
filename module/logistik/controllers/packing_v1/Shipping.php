<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shipping extends Logistik_Controller {

    public function index()
    {
        $this->_restrict_access('logistik_packing_alredy');
        $this->session->set_userdata('packing_state', 'packing_v1/shipping');
        $this->load->model('cs_model');

        $tl = $this->session->userdata('tim_leader');
        $team_cs_id = 0;
        if(!empty($tl) && isset($tl->team_cs_id))
        {
            $team_cs_id = $tl->team_cs_id;
        }

        $this->_set_data([
            'title' => 'Pesanan Sudah di Packing',
            'packing_state' => 'packing_v1/shipping',
            'list_cs' => $this->cs_model->get_active([
                'role_id' => $this->role_active['role_id'],
                'team_cs_id' => $team_cs_id
            ])->result()
        ]);

        $this->blade->view('inc/logistik/packing/shipping_v1', $this->data);
    }

    public function update_shipping()
    {
        // $this->_restrict_access('logistik_packing_action_shipping','rest');

        $id = (int) $this->input->post('order_id');
        $logistic_id = (int) $this->input->post('logistic_id');
        $shipping_code = $this->input->post('shipping_code');

        $this->load->model(['orders_model','master_model','logistics_process_model']);
        $res = $this->orders_model->get_byid_v1($id);
        $data = $res->first_row();
        $profile = $this->session->userdata('profile');

        $logistics_status = $this->master_model->logistics_status(5)->first_row();
        $label_logistics_status = isset($logistics_status->label)?$logistics_status->label:'Pengiriman';

        $order_status = [
            'logistic_id' => $logistic_id,
            'shipping_code' => $shipping_code
        ];
        $logistik_process = [
            'order_id' => $id,
            'user_id' => $profile['user_id'],
            'logistics_status_id' => 5,
            'status' => $label_logistics_status,
            'notes' => "Perubahan No. Resi menjadi <b>$shipping_code</b>",
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
