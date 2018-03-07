<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders_model extends Penjualan_Model {
    protected
        $datatable_param = NULL,
        $table = 'orders',
        $orderable_field = ['name','franchise_name','franchise_name','jumlah_cs','status'],
        $fillable_field = ['payment_method_id','logistic_id','order_status_id','logistics_status_id','order_status','logistics_status','call_method_id','customer_info','customer_address'],
        $searchable_field = ['payment_method_id','logistic_id','order_status_id','logistics_status_id','call_method_id','order_status','logistics_status','shipping_code','call_method','order_code','customer_info','customer_address'];

    function get_datatable_v1($params = [], $only_self = TRUE)
    {
        $select = [];
        $join = [];
        $where = [];
        $ordering = 'ORDER BY created_at ASC';

        if($params['order_status_id'] == 4) $ordering = 'ORDER BY created_at DESC';

        if(isset($params['order_status_id']) && $params['order_status_id'] != 1)
        {
            $history_order_status_id = ($params['order_status_id']<7)?$params['order_status_id']:6;
            $join[] = "LEFT JOIN (SELECT
                z.order_id, z.order_status_id, z.user_id, zo.username
                FROM orders_process z
                LEFT JOIN sso_user zo ON z.user_id = zo.user_id
                WHERE z.order_status_id = {$history_order_status_id}
                GROUP BY z.order_id, z.order_status_id, z.user_id, zo.username) d ON a.order_id = d.order_id";
            $select[] = 'd.username';
        }
        if(
            $only_self && isset($params['user_id']) &&
            !in_array($params['order_status_id'], [1,4]))
        {
            $user_id = (int) $params['user_id'];
            $where[] = "d.user_id = $user_id";
        }

        if($params['order_status_id'] < 7)
        {
            $where[] = "a.order_status_id = {$params['order_status_id']}";
        }
        else
        {
            $ordering = 'ORDER BY created_at DESC';
            $where[] = "a.order_status_id >= {$params['order_status_id']}";
        }

        if(empty($select)) $select = ''; else $select = ", ".implode(", ",$select);
        if(empty($join)) $join = ''; else $join = implode(" \n",$join);
        if(empty($where)) $where = ''; else $where = " AND ".implode(" AND ",$where);

        if(!isset($params['order_status_id'])) $params['order_status_id'] = 1;

        $sql = "SELECT
                a.*, b.icon AS call_method_icon, c.name AS payment_method,
                (SELECT package_name FROM orders_cart WHERE order_id = a.order_id LIMIT 1) AS package_name $select
            FROM orders a
            LEFT JOIN master_call_method b ON a.call_method_id = b.call_method_id
            LEFT JOIN master_payment_method c ON a.payment_method_id = c.payment_method_id
            $join
            WHERE
            a.version = 1
            $where
            $ordering";

        $sql = $this->_combine_datatable_param($sql);
        $sql_count = $this->_combine_datatable_param("SELECT a.*
            FROM orders a
            $join
            WHERE
            a.version = 1
            $where", TRUE);
        return [
            'row' => $this->db->query($sql)->result(),
            'total' => $this->db->query($sql_count)->row()->count
        ];
    }

    function cart_v1($id)
    {
        $orders_cart = $this->db->get_where('orders_cart', ['order_id' => $id, 'version' => 1])->result();
        $orders_cart_package = [];
        foreach ($orders_cart as $key => $value) {
            if($value->is_package)
            $orders_cart_package[$value->product_package_id]['info'] = (object) [
                'product_package_id' => $value->product_package_id,
                'package_name' => $value->package_name,
                'price_type' => $value->price_type,
                'package_price' => $value->package_price
            ];
            $orders_cart_package[$value->product_package_id]['cart'][] = (object) [
                'cart_id' => $value->cart_id,
                'order_id' => $value->order_id,
                'product_id' => $value->product_id,
                'product_merk' => $value->product_merk,
                'product_name' => $value->product_name,
                'price' => $value->price,
                'qty' => $value->qty,
                'weight' => $value->weight,
                'price_type' => $value->price_type
            ];
        }
        return $orders_cart_package;
    }

    function get_byid_v1($id)
    {
        $sql = "SELECT
                a.*, b.icon AS call_method_icon,c.name AS payment_method
            FROM orders a
            LEFT JOIN master_call_method b ON a.call_method_id = b.call_method_id
            LEFT JOIN master_payment_method c ON a.payment_method_id = c.payment_method_id
            WHERE a.version = 1 AND a.order_id = ? LIMIT 1";
        return $this->db->query($sql, [$id]);
    }

    function upd($id, $params)
    {
        $this->db->where('order_id', $id);
        return $this->db->update($this->table, $this->_sanity_field($params, $this->fillable_field));
    }

    function clear_cart_package($order_id = 0)
    {
        return $this->db->delete('orders_cart', ['order_id' => $order_id]);
    }

    function upd_cart_package($order_id, $product_package_id)
    {
        $sql = "INSERT INTO orders_cart (
            order_id, product_id, product_package_id, product_merk, product_name, package_name, price, qty, weight, is_package, price_type, package_price)
        SELECT ?, a.product_id, a.product_package_id, a.merk, a.name, b.name, a.price, a.qty, a.weight, 1, b.price_type, b.price
        FROM product_package b
        LEFT JOIN product_package_list a ON b.product_package_id = a.product_package_id
        WHERE b.product_package_id = ?";
        return $this->db->query($sql, [$order_id, $product_package_id]);
    }
}
