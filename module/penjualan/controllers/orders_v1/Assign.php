<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assign extends Penjualan_Controller {

    public function orders()
    {
        $order_id = json_decode(base64_decode($this->input->post('order_id')));
        $user_id = json_decode(base64_decode($this->input->post('user_id')));
        $type = $this->input->post('type');
        $total = (int) $this->input->post('total');

        dd($order_id);
        dd($user_id);
        dd($type);
        dd($total);
    }
}
