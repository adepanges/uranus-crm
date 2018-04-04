<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Double_orders_model extends Penjualan_Model {
    protected
        $datatable_param = NULL,
        $table = 'orders',
        $orderable_field = ['customer_name','customer_telephone','double_reason','created_at','status'],
        $fillable_field = ['customer_id', 'customer_name', 'customer_telephone', 'double_reason', 'created_at', 'status'],
        $searchable_field = ['customer_name','customer_telephone','double_reason'];

    function get_datatable($params = [], $only_self = TRUE)
    {

        $sql = "SELECT * FROM orders_double WHERE status = 1";

        $sql = $this->_combine_datatable_param($sql);
        $sql_count = $this->_combine_datatable_param($sql, TRUE);
        return [
            'row' => $this->db->query($sql)->result(),
            'total' => $this->db->query($sql_count)->row()->count
        ];
    }
}
