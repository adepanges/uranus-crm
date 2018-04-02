<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_package_model extends API_Model {

    function list()
    {
        return $this->db->get_where('product_package', ['status' => 1]);
    }
}
