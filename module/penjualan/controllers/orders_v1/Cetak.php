<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cetak extends Penjualan_Controller {

    public function invoice($id = '')
    {
        if(empty($id)) redirect();
        $id = (int) $id;

        $this->load->model(['invoice_model','orders_model']);
        $res = $this->invoice_model->get_invoice($id);

        $data = $res->first_row();

        if(isset($data->customer)) $data->customer = json_decode($data->customer);
        if(isset($data->customer_address)) $data->customer_address = json_decode($data->customer_address);
        if(isset($data->order_cart)) $data->order_cart = $this->orders_model->parse_cart_v1(json_decode($data->order_cart));

        $this->blade->view('cetak/invoice', [
            'invoice' => $data
        ]);
    }
}
