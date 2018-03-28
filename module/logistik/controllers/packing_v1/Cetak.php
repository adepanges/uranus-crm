<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cetak extends Logistik_Controller {

    public function label($id = '')
    {
        if(empty($id)) redirect();
        $id = explode(',',base64_decode($id));

        $this->load->model(['invoice_model','orders_model']);
        $res = $this->invoice_model->get_available_print($id);

        $data = $res->result();
        foreach ($data as $key => $value) {
            $value->customer = json_decode($value->customer);
            $value->customer_address = json_decode($value->customer_address);
            $value->order_cart = $this->orders_model->parse_cart_v1(json_decode($value->order_cart));
            $data[$key] = $value;
        }
        $this->blade->view('cetak/label', [
            'invoices' => $data
        ]);
    }
}
