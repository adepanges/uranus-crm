<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_package_model extends Penjualan_Model {
    protected
        $datatable_param = NULL,
        $table = 'product_package',
        $orderable_field = ['code','name','price','price_type'],
        $fillable_field = [],
        $searchable_field = ['code','name','price'];

    function get_datatable()
    {
        $sql = "SELECT * FROM product_package WHERE status = 1";

        $sql = $this->_combine_datatable_param($sql);
        $sql_count = $this->_combine_datatable_param($sql, TRUE);
        return [
            'row' => $this->db->query($sql)->result(),
            'total' => $this->db->query($sql_count)->row()->count
        ];
    }
}
