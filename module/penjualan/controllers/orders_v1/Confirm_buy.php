<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Confirm_buy extends Penjualan_Controller {

    public function index()
    {
        $this->_restrict_access('penjualan_orders_confirm_buy');
        $this->session->set_userdata('orders_state', 'orders_v1/confirm_buy');
        $this->_set_data([
            'title' => 'Confirm Buy Orders'
        ]);

        $this->blade->view('inc/penjualan/orders/confirm_buy_v1', $this->data);
    }

    function verify_payment($id)
    {
        $this->_restrict_access('penjualan_orders_action_verify_payment');
        $id = (int) $id;
        $this->load->model(['orders_model','orders_process_model','master_model']);
        $res = $this->orders_model->get_byid_v1($id);
        $data = $res->first_row();
        $profile = $this->session->userdata('profile');

        if(!$res->num_rows() || !in_array($data->order_status_id, [5])) redirect('orders_v1');

        $follow_up_status = $this->master_model->order_status(6)->first_row();

        $label_status = isset($follow_up_status->label)?$follow_up_status->label:'Verify Payment';
        $order_status = [
            'order_status_id' => 6,
            'order_status' => $label_status
        ];
        $order_process = [
            'order_id' => $id,
            'user_id' => $profile['user_id'],
            'order_status_id' => 6,
            'status' => $label_status,
            'notes' => "Customer telah membayar, pesanan dalam proses $label_status, informasi oleh <b>{$profile['first_name']} {$profile['last_name']}</b>",
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
