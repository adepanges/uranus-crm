<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_model extends Penjualan_Model {

    function call_method()
    {
        return $this->db->get_where('master_call_method', ['status' => 1]);
    }

    function payment_method()
    {
        return $this->db->get_where('master_payment_method', ['status' => 1]);
    }

    function order_status($id = 0)
    {
        if($id)
        {
            $this->db->where('order_status_id', $id);
        }
        return $this->db->get_where('master_order_status');
    }
}
