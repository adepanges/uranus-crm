<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Follow_up extends Penjualan_Controller {

    public function index($id = 0)
    {
        $this->_restrict_access('penjualan_orders_follow_up');
        redirect('orders_v1/detail/index/'.$id);
    }

    function cancel()
    {
        $this->_restrict_access('penjualan_orders_action_cancel', 'rest');
        $this->load->model(['orders_model','master_model','orders_process_model']);
        $id = (int) $this->input->post('order_id');
        $notes = (!empty($this->input->post('notes')))?$this->input->post('notes'):$this->input->post('notes_value');

        $profile = $this->session->userdata('profile');
        $res = $this->orders_model->get_byid_v1($id);
        $orders = $res->first_row();

        if(!$res->num_rows() || $orders->order_status_id != 2) {
            $this->_response_json([
                'status' => 0,
                'message' => 'Orders tidak dapat dibatalkan'
            ]);
        }

        $follow_up_status = $this->master_model->order_status(4)->first_row();

        $label_status = isset($follow_up_status->label)?$follow_up_status->label:'Cancel';
        $order_status = [
            'order_status_id' => 4,
            'order_status' => $label_status
        ];
        $order_process = [
            'order_id' => $id,
            'user_id' => $profile['user_id'],
            'order_status_id' => 4,
            'status' => $label_status,
            'notes' => trim($notes),
            'event_postback_status' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $res1 = $this->orders_model->upd($id, $order_status);
        $res2 = $this->orders_process_model->add($order_process);

        if($res1 && $res2)
        {
            $this->session->set_userdata('orders_follow_up', '');
            $this->_response_json([
                'status' => 1,
                'message' => 'Orders berhasil dibatalkan'
            ]);
        }
        else
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Orders gagal dibatalkan'
            ]);
        }
    }

    function pending()
    {
        $this->_restrict_access('penjualan_orders_action_pending', 'rest');
        $this->load->model(['orders_model','master_model','orders_process_model']);
        $id = (int) $this->input->post('order_id');
        $notes = (!empty($this->input->post('notes')))?$this->input->post('notes'):$this->input->post('notes_value');

        $profile = $this->session->userdata('profile');
        $res = $this->orders_model->get_byid_v1($id);
        $orders = $res->first_row();

        if(!$res->num_rows() || !in_array($orders->order_status_id, [2,3])) {
            $this->_response_json([
                'status' => 0,
                'message' => 'Orders tidak dapat dipending'
            ]);
        }

        $follow_up_status = $this->master_model->order_status(3)->first_row();

        $label_status = isset($follow_up_status->label)?$follow_up_status->label:'Pending';
        $order_status = [
            'order_status_id' => 3,
            'order_status' => $label_status
        ];
        $order_process = [
            'order_id' => $id,
            'user_id' => $profile['user_id'],
            'order_status_id' => 3,
            'status' => $label_status,
            'notes' => trim($notes),
            'event_postback_status' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $res1 = $this->orders_model->upd($id, $order_status);
        $res2 = $this->orders_process_model->add($order_process);

        if($res1 && $res2)
        {
            $this->session->set_userdata('orders_follow_up', '');
            $this->_response_json([
                'status' => 1,
                'message' => 'Orders berhasil dipending'
            ]);
        }
        else
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Orders gagal dipending'
            ]);
        }
    }

    function confirm_buy($id)
    {
        $this->_restrict_access('penjualan_orders_action_confirm_buy');
        $id = (int) $id;
        $this->load->model(['orders_model','orders_process_model','master_model']);
        $res = $this->orders_model->get_byid_v1($id);
        $data = $res->first_row();
        $profile = $this->session->userdata('profile');

        if(!$res->num_rows() || !in_array($data->order_status_id, [2, 3])) redirect($this->session->userdata('orders_state'));

        $follow_up_status = $this->master_model->order_status(5)->first_row();

        $label_status = isset($follow_up_status->label)?$follow_up_status->label:'Confirm Buy';
        $order_status = [
            'order_status_id' => 5,
            'order_status' => $label_status
        ];
        $order_process = [
            'order_id' => $id,
            'user_id' => $profile['user_id'],
            'order_status_id' => 5,
            'status' => $label_status,
            'notes' => "Pesanan $label_status oleh <b>{$profile['first_name']} {$profile['last_name']}</b>",
            'event_postback_status' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $res1 = $this->orders_model->upd($id, $order_status);
        $res2 = $this->orders_process_model->add($order_process);

        if($res1 && $res2)
        {
            $this->session->set_userdata('orders_follow_up', '');
            redirect($this->session->userdata('orders_state'));
        }
        else redirect('orders_v1/detail/index/'.$id);
    }
}
