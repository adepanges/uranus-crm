<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detail extends Penjualan_Controller {

    public function index($id = 0)
    {
        $this->_restrict_access('penjualan_orders_detail');
        $id = (int) $id;
        $this->load->model(['orders_model','master_model','orders_process_model']);

        $res = $this->orders_model->get_byid_v1($id);

        if(!$res->num_rows()) redirect('orders_v1');

        $orders = $res->first_row();
        if(isset($orders->customer_info)) $orders->customer_info = json_decode($orders->customer_info);
        if(isset($orders->customer_address)) $orders->customer_address = json_decode($orders->customer_address);

        $orders_cart_package = $this->orders_model->cart_v1($id);
        $orders_cart_package_id = 0;
        foreach ($orders_cart_package as $key => $value) {
            $orders_cart_package_id = $value['info']->product_package_id;
        }

        $product_package = $this->master_model->product_package()->result();
        foreach ($product_package as $key => $value) {
            $value->product_list = $this->master_model->product_package_list([
                'product_package_id' => $value->product_package_id
            ])->result();
            $product_package[$key] = $value;
        }

        $this->_set_data([
            'title' => 'Detail Pesanan',
            'orders' => $orders,
            'attr_readonly' => 'readonly',
            'master_payment_method' => $this->master_model->payment_method()->result(),
            'master_call_method' => $this->master_model->call_method()->result(),
            'master_wilayah_provinsi' => $this->master_model->wilayah_provinsi()->result(),
            'master_logistics' => $this->master_model->logistics()->result(),
            'orders_cart_package_id' => $orders_cart_package_id,
            'master_product_package' => $product_package,
            'orders_cart_package' => $orders_cart_package,
            'orders_process' => $this->orders_process_model->get($id)->result()
        ]);

        $this->blade->view('inc/penjualan/orders/detail_v1', $this->data);
    }
}
