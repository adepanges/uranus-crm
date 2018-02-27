<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Follow_up extends Penjualan_Controller {

    public function index($id = 0)
    {
        $this->_restrict_access('penjualan_orders');
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

        $this->_set_data([
            'title' => 'Detail Pesanan',
            'orders' => $orders,
            'attr_readonly' => 'readonly',
            'master_payment_method' => $this->master_model->payment_method()->result(),
            'orders_cart_package' => $orders_cart_package,
            'orders_process' => $this->orders_process_model->get($id)->result()
        ]);

        $this->blade->view('inc/penjualan/orders/detail_v1', $this->data);
    }
}
