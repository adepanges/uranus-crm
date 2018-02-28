<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders_model extends Penjualan_Model {
    protected
        $datatable_param = NULL,
        $table = 'orders',
        $orderable_field = ['name','franchise_name','franchise_name','jumlah_cs','status'],
        $fillable_field = ['payment_method_id','logistic_id','order_status_id','logistics_status_id','order_status','logistics_status','customer_info','customer_address'],
        $searchable_field = ['payment_method_id','logistic_id','order_status_id','logistics_status_id','call_method_id','order_status','logistics_status','shipping_code','call_method','order_code','customer_info','customer_address'];

    function get_datatable_v1($params = [])
    {
        $ordering = 'ORDER BY created_at ASC';
        if($params['order_status_id'] == 4) $ordering = 'ORDER BY created_at DESC';
        $sql = "SELECT
                a.*, b.icon AS call_method_icon,
                (SELECT package_name FROM orders_cart WHERE order_id = a.order_id LIMIT 1) AS package_name,
                c.name AS payment_method
            FROM orders a
            LEFT JOIN master_call_method b ON a.call_method_id = b.call_method_id
            LEFT JOIN master_payment_method c ON a.payment_method_id = c.payment_method_id
            WHERE a.order_status_id = {$params['order_status_id']} AND a.version = 1 $ordering";

        $sql = $this->_combine_datatable_param($sql);
        $sql_count = $this->_combine_datatable_param("SELECT * FROM orders WHERE order_status_id = {$params['order_status_id']} AND version = 1", TRUE);
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
}
