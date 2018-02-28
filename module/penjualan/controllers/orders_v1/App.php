<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends Penjualan_Controller {

    public function index()
    {
        $this->_restrict_access('penjualan_orders_new');
        $this->session->set_userdata('orders_state', 'orders_v1/app');
        $this->_set_data([
            'title' => 'Orders New'
        ]);

        $this->blade->view('inc/penjualan/orders/app_v1', $this->data);
    }

    public function follow_up($id)
    {
        $this->_restrict_access('penjualan_orders_action_follow_up');
        $id = (int) $id;
        $this->load->model(['orders_model','orders_process_model','master_model']);
        $res = $this->orders_model->get_byid_v1($id);
        $data = $res->first_row();
        $profile = $this->session->userdata('profile');

        if(!$res->num_rows() || !in_array($data->order_status_id, [1,3,4,5])) redirect('orders_v1');

        $follow_up_status = $this->master_model->order_status(2)->first_row();

        $label_status = isset($follow_up_status->label)?$follow_up_status->label:'Follow Up';
        $order_status = [
            'order_status_id' => 2,
            'order_status' => $label_status
        ];
        $order_process = [
            'order_id' => $id,
            'user_id' => $profile['user_id'],
            'order_status_id' => 2,
            'status' => $label_status,
            'notes' => "Pesanan sedang di $label_status oleh <b>{$profile['first_name']} {$profile['last_name']}</b>",
            'event_status' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $res1 = $this->orders_model->upd($id, $order_status);
        $res2 = $this->orders_process_model->add($order_process);

        if($res1 && $res2)
        {
            $this->session->set_userdata('orders_follow_up', ['order_id' => $id, 'created_at' => $order_process['created_at']]);
            redirect('orders_v1/follow_up/index/'.$id);
        }
        else redirect($this->session->userdata('orders_state'));
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
            'order_status_id' => 2,
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
