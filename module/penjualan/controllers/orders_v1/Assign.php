<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assign extends Penjualan_Controller {

    public function orders()
    {
        $order_id = (array) json_decode(base64_decode($this->input->post('order_id')));
        $user_id = (array) json_decode(base64_decode($this->input->post('user_id')));
        $type = $this->input->post('type');
        $total = (int) $this->input->post('total_orders');

        if(empty($type)) $type = 'bulk';
        if(empty($user_id)) {
            $this->_response_json([
                'status' => 0,
                'message' => 'CS belum dipilih'
            ]);
        }

        if($total > 200) {
            $this->_response_json([
                'status' => 0,
                'message' => 'Limit proses assign hanya 200, agar proses assign tidak terlalu lama'
            ]);
        }

        $this->load->model('assign_model');

        $data_orders = [];
        switch($type){
            case 'selected':
                if(empty($order_id)) {
                    $this->_response_json([
                        'status' => 0,
                        'message' => 'Orders belum dipilih'
                    ]);
                }
                $data_orders = $this->assign_model->get_orders_active_byid($order_id)->result();
            break;

            case 'bulk':
                $data_orders = $this->assign_model->get_orders_active($total)->result();
            break;
        }

        $data_user = $this->assign_model->get_user_byid($user_id)->result();
        $user_pos_index = 0;
        $user_post_max = count($data_user) - 1;

        if($user_post_max < 0)
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'CS tidak ditemukan'
            ]);
        }

        foreach ($data_orders as $key => $value) {
            if(isset($data_user[$user_pos_index]))
            {
                $this->assign_to_cs($value->order_id, $data_user[$user_pos_index]);
            }

            $user_pos_index++;
            if($user_pos_index > $user_post_max) $user_pos_index = 0;
        }

        $this->_response_json([
            'status' => 1,
            'message' => 'Proses assign telah selesai'
        ]);
    }

    protected function assign_to_cs($orders_id, $cs_user)
    {
        $this->load->model(['orders_model','orders_process_model','master_model']);

        $res = $this->orders_model->get_byid_v1($orders_id);
        $data = $res->first_row();
        $profile = $this->session->userdata('profile');
        $follow_up_status = $this->master_model->order_status(10)->first_row();


        $label_status = isset($follow_up_status->label)?$follow_up_status->label:'Assign';
        $order_status = [
            'franchise_id' => $this->franchise->franchise_id,
            'order_status_id' => 10,
            'order_status' => $label_status
        ];
        $order_process = [
            'order_id' => $orders_id,
            'user_id' => $cs_user->user_id,
            'order_status_id' => 10,
            'status' => $label_status,
            'notes' => "Ditugaskan kepada <b>{$cs_user->first_name} {$cs_user->last_name}</b> oleh <b>{$profile['first_name']} {$profile['last_name']}</b>",
            'event_postback_status' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->orders_model->upd($orders_id, $order_status);
        $this->orders_process_model->add($order_process);
    }
}
