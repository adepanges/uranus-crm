<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders_model extends Penjualan_Model {
    protected
        $datatable_param = NULL,
        $table = 'orders',
        $orderable_field = ['name','franchise_name','franchise_name','jumlah_cs','status'],
        $fillable_field = ['payment_method_id','logistic_id','order_status_id','logistics_status_id','order_status','logistics_status','customer_info','customer_address'],
        $searchable_field = ['payment_method_id','logistic_id','order_status_id','logistics_status_id','call_method_id','order_status','logistics_status','shipping_code','call_method','order_code','customer_info','customer_address'];

    function get_datatable_v1()
    {
        $sql = "SELECT
                a.*, b.icon AS call_method_icon,
                (SELECT package_name FROM orders_cart WHERE order_id = a.order_id LIMIT 1) AS package_name,
                c.name AS payment_method
            FROM orders a
            LEFT JOIN master_call_method b ON a.call_method_id = b.call_method_id
            LEFT JOIN master_payment_method c ON a.payment_method_id = c.payment_method_id
            WHERE a.order_status_id = 1 AND a.version = 1 ORDER BY created_at ASC";

        $sql = $this->_combine_datatable_param($sql);
        $sql_count = $this->_combine_datatable_param("SELECT * FROM orders", TRUE);
        return [
            'row' => $this->db->query($sql)->result(),
            'total' => $this->db->query($sql_count)->row()->count
        ];
    }
}
