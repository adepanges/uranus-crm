<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Follow_up extends Penjualan_Controller {

    public function index($id = 0)
    {
        $this->_restrict_access('penjualan_orders_follow_up');
        $id = (int) $id;
        $this->load->model(['orders_model','master_model','orders_process_model']);

        $res = $this->orders_model->get_byid_v1($id);
        $orders = $res->first_row();

        if(!$res->num_rows() || $orders->order_status_id != 2) {
            $this->session->set_userdata('orders_follow_up','');
            redirect('orders_v1');
        }

        if(isset($orders->customer_info)) $orders->customer_info = json_decode($orders->customer_info);
        if(isset($orders->customer_address)) $orders->customer_address = json_decode($orders->customer_address);

        $orders_cart_package = $this->orders_model->cart_v1($id);

        $reason_cancel = ['Tidak jadi beli','Tidak merasa pesan','Double Order','Nomor palsu'];
        $reason_pending = ['Sudah di WhatsApp','Nomor WhatsApp tidak keluar','Tidak diangkat','Minta dihubungi lagi nanti'];

        $this->_set_data([
            'title' => 'Detail Pesanan',
            'orders' => $orders,
            'attr_readonly' => 'readonly',
            'reason_cancel' => $reason_cancel,
            'reason_pending' => $reason_pending,
            'master_payment_method' => $this->master_model->payment_method()->result(),
            'orders_cart_package' => $orders_cart_package,
            'orders_process' => $this->orders_process_model->get($id)->result()
        ]);

        $this->blade->view('inc/penjualan/orders/detail_v1', $this->data);
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
            'event_status' => 0,
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

        if(!$res->num_rows() || $orders->order_status_id != 2) {
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
            'event_status' => 0,
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

        if(!$res->num_rows() || !in_array($data->order_status_id, [2])) redirect('orders_v1');

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
            'event_status' => 0,
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
