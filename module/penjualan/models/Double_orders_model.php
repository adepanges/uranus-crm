<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Double_orders_model extends Penjualan_Model {
    protected
        $datatable_param = NULL,
        $table = 'orders',
        $orderable_field = ['customer_name','customer_telephone','double_reason','created_at','status'],
        $fillable_field = ['customer_id', 'customer_name', 'customer_telephone', 'double_reason', 'created_at', 'status'],
        $searchable_field = ['customer_name','customer_telephone','double_reason'];

    function get_datatable()
    {
        $sql = "SELECT
            	a.*
            FROM orders_double a
            WHERE a.status = 1 AND (SELECT order_status_id
            FROM orders
            WHERE `version` = 1 AND
            is_deleted = 0 AND orders_double_id = a.orders_double_id
            ORDER BY order_status_id DESC LIMIT 1) < 2";

        $sql = $this->_combine_datatable_param($sql);
        $sql_count = $this->_combine_datatable_param($sql, TRUE);
        return [
            'row' => $this->db->query($sql)->result(),
            'total' => $this->db->query($sql_count)->row()->count
        ];
    }

    function get_follow_up_datatable($user_id = 0)
    {
        $user_id = (int) $user_id;
        $sql = "SELECT
            	a.*
            FROM orders_double a
            WHERE a.status = 1 AND
            $user_id IN (SELECT w.user_id
                FROM orders z
                LEFT JOIN orders_process w ON z.order_id = w.order_id
                WHERE z.`version` = 1 AND
                z.is_deleted = 0 AND
                z.orders_double_id = a.orders_double_id AND
                z.order_status_id > 1)";

        $sql = $this->_combine_datatable_param($sql);
        $sql_count = $this->_combine_datatable_param($sql, TRUE);
        return [
            'row' => $this->db->query($sql)->result(),
            'total' => $this->db->query($sql_count)->row()->count
        ];
    }

    function solve($orders_double_id)
    {
        $this->db->where_in('orders_double_id', $orders_double_id);
        return $this->db->update('orders_double', [
            'status' => 0
        ]);
    }

    function get_byid($id)
    {
        return $this->db->limit(1)->get_where('orders_double', [
            'orders_double_id' => (int) $id
        ]);
    }

    function pulihkan($order_id = 0)
    {
        $this->db->where_in('order_id', $order_id);
        return $this->db->update('orders', [
            'orders_double_id' => NULL,
        ]);
    }

    function get_orders($id)
    {
        $sql = "SELECT
                a.*, b.icon AS call_method_icon, c.name AS payment_method,
                (SELECT package_name FROM orders_cart WHERE order_id = a.order_id AND is_package = 1 LIMIT 1) AS package_name
            FROM orders a
            LEFT JOIN master_call_method b ON a.call_method_id = b.call_method_id
            LEFT JOIN master_payment_method c ON a.payment_method_id = c.payment_method_id
            WHERE
            a.version = 1 AND a.is_deleted = 0 AND
            a.orders_double_id = ?
            ORDER BY a.order_id ASC";
        return $this->db->query($sql, [$id]);
    }
}
