<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Verify extends Penjualan_Controller {

    public function index()
    {
        $this->_restrict_access('penjualan_orders_verify_payment');
        $this->session->set_userdata('orders_state', 'orders_v1/verify');
        $this->load->model(['master_model']);

        $this->_set_data([
            'title' => 'Verify Payment Orders',
            'master_payment_method' => $this->master_model->payment_method()->result(),
        ]);

        $this->blade->view('inc/penjualan/orders/verify_v1', $this->data);
    }

    function sale()
    {
        $this->_restrict_access('penjualan_orders_action_sale');
        $id = (int) $this->input->post('order_id');
        $this->load->model(['orders_model','orders_process_model','master_model','invoice_model']);
        $res = $this->orders_model->get_byid_v1($id);
        $data = $res->first_row();
        $profile = $this->session->userdata('profile');

        if(!$res->num_rows() || !in_array($data->order_status_id, [6])) redirect('orders_v1');

        $follow_up_status = $this->master_model->order_status(7)->first_row();

        $label_status = isset($follow_up_status->label)?$follow_up_status->label:'Sale';
        $order_status = [
            'order_status_id' => 7,
            'payment_method_id' => (int) $this->input->post('payment_method_id'),
            'order_status' => $label_status
        ];
        $order_process = [
            'order_id' => $id,
            'user_id' => $profile['user_id'],
            'order_status_id' => 7,
            'status' => $label_status,
            'notes' => "Payment diverifikasi oleh <b>{$profile['first_name']} {$profile['last_name']}</b>",
            'event_postback_status' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $paid_date = !empty($this->input->post('paid_date'))?$this->input->post('paid_date'):$order_process['created_at'];
        $res1 = $this->invoice_model->publish_v1($id, $paid_date);
        $res2 = $this->orders_model->upd($id, $order_status);
        $res3 = $this->orders_process_model->add($order_process);

        if($res1 && $res1 && $res2)
        {

            $this->session->set_userdata('orders_follow_up', '');
            $this->_response_json([
                'status' => 1,
                'message' => 'Sukses, orders diteruskan ke tim logistik'
            ]);
        }
        else
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Gagal'
            ]);
        }
    }
}
