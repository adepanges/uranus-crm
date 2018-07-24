<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assign_model extends Penjualan_Model {

    function get_orders_active($limit = 10, $product_package_id)
    {
        $join = '';
        $where = '';

        if(!empty($product_package_id))
        {
            $product_package_id = implode(',', $product_package_id);
            $join = 'LEFT JOIN (SELECT DISTINCT order_id, product_package_id
                    FROM orders_cart WHERE product_package_id IN ('.$product_package_id.')) b ON a.order_id = b.order_id';
            $where = 'AND b.product_package_id IS NOT NULL';
        }
        $sql = "SELECT a.order_id
            FROM orders a
            $join
            WHERE
            a.version = 1 AND
            a.order_status_id = 1 AND
            (a.orders_double_id IS NULL OR a.orders_double_id = 0) AND
            a.is_deleted = 0 $where
            ORDER BY created_at ASC
            LIMIT ?";
        return $this->db->query($sql, [$limit]);
    }

    function get_orders_active_byid($order_id, $product_package_id)
    {
        if(is_object($order_id)) $order_id = (array) $order_id;
        else if(!is_array($order_id)) $order_id = [$order_id];

        $this->db->select('order_id');
        $this->db->where_in('order_id', $order_id);
        $this->db->where('order_status_id', 1);
        $this->db->where('version', 1);
        $this->db->where('is_deleted', 0);
        $this->db->where('(orders_double_id IS NULL OR orders_double_id = 0)', NULL, FALSE);
        return $this->db->get('orders');
    }

    function get_user_byid($user_id)
    {
        if(is_object($user_id)) $user_id = (array) $user_id;
        else if(!is_array($user_id)) $user_id = [$user_id];

        $this->db->where_in('user_id', $user_id);
        $this->db->where('status', 1);
        return $this->db->get('sso_user');
    }
}
