<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders_model extends API_Model {

    function add($orders)
    {
        $sql = "INSERT INTO orders (
                version,
                created_at,
                shipping_code,
                order_code,
                customer_id,
                customer_address_id,
                customer_info,
                customer_address,
                payment_method_id,
                logistic_id,
                order_status_id,
                logistics_status_id,
                call_method_id,
                order_status,
                logistics_status,
                call_method,
                total_price
            )
            SELECT
                ? AS version,
                ? AS created_at,
                '' AS shipping_code,
                ? AS order_code,
                ? AS customer_id,
                ? AS customer_address_id,
                ? AS customer_info,
                ? AS customer_address,
                ? AS payment_method_id,
                ? AS logistic_id,
            	a.order_status_id,
            	b.logistics_status_id,
            	c.call_method_id,
                a.label AS order_status,
                b.label AS logistics_status,
                c.name AS call_method,
            	CASE
            	    WHEN d.price_type = 'PACKAGE' THEN d.price
            	    ELSE (SELECT SUM(price) FROM product_package_list WHERE product_package_id = d.product_package_id AND `status` = 1)
            	END AS total_price
            FROM master_order_status a
            LEFT JOIN master_logistics_status b ON b.logistics_status_id = ?
            LEFT JOIN master_call_method c ON c.call_method_id = ?
            LEFT JOIN product_package d ON d.product_package_id = ?
            WHERE a.order_status_id = ?
            LIMIT 1";
        return $this->db->query($sql, [
            'version' => $orders['version'],
            'created_at' => $orders['created_at'],
            'order_code' => $orders['order_code'],
            'customer_id' => (int) $orders['customer_id'],
            'customer_address_id' => (int) $orders['customer_address_id'],
            'customer_info' => $orders['customer_info'],
            'customer_address' => $orders['customer_address'],
            'payment_method_id' => (int) $orders['payment_method_id'],
            'logistic_id' => (int) $orders['logistic_id'],
            'logistics_status_id' => (int) $orders['logistics_status_id'],
            'call_method_id' => (int) $orders['call_method_id'],
            'product_package_id' => (int) $orders['product_package_id'],
            'order_status_id' => (int) $orders['order_status_id']
        ]);
    }

    function cart_add($order_id, $product_package_id)
    {
        $sql = "INSERT INTO orders_cart (
            order_id,
            product_id,
            product_package_id,
            product_merk,
            product_name,
            package_name,
            price,
            qty,
            weight,
            is_package,
            price_type,
            package_price
        )
        SELECT
            ?,
            a.product_id,
            a.product_package_id,
            a.merk,
            a.name,
            b.name,
            a.price,
            a.qty,
            a.weight,
            1,
            b.price_type,
            b.price
        FROM product_package b
        LEFT JOIN product_package_list a ON b.product_package_id = a.product_package_id
        WHERE b.product_package_id = ?";
        return $this->db->query($sql, [
            'order_id' => (int) $order_id,
            'product_package_id' => (int) $product_package_id
        ]);
    }

    function get_last_order()
    {
        $sql = "SELECT * FROM orders
        WHERE
        YEAR(created_at) = ? AND
        MONTH(created_at) = ? AND
        DAY(created_at) = ?
        ORDER BY order_id DESC
        LIMIT 1";
        return $this->db->query($sql, [
            date('Y'), (int) date('m'), date('d')
        ]);
    }
}
