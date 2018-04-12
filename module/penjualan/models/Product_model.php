<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends Penjualan_Model {

    function get_datatable()
    {
        $sql = "SELECT * FROM product WHERE status = 1";

        $sql = $this->_combine_datatable_param($sql);
        $sql_count = $this->_combine_datatable_param($sql, TRUE);
        return [
            'row' => $this->db->query($sql)->result(),
            'total' => $this->db->query($sql_count)->row()->count
        ];
    }

    function get()
    {
        return $this->db->get_where('product_package', ['status' => 1]);
    }
}
