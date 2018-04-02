<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_model extends API_Model {

    function logistics()
    {
        return $this->db->get_where('master_logistics', ['status' => 1]);
    }

    function call_method()
    {
        return $this->db->get_where('master_call_method', ['status' => 1]);
    }

    function payment_method()
    {
        return $this->db->get_where('master_payment_method', ['status' => 1]);
    }

    function product_package($product_package_id = 0)
    {
        if($product_package_id != 0)
        {
            $this->db->where('product_package_id', $product_package_id);
        }
        return $this->db->get_where('product_package', ['status' => 1]);
    }
}
