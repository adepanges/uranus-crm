<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders_model extends Logistik_Model {
    protected
        $datatable_param = NULL,
        $table = 'orders',
        $orderable_field = ['name','franchise_name','franchise_name','jumlah_cs','status'],
        $fillable_field = ['logistic_id','order_status_id','logistics_status_id','order_status','logistics_status','shipping_code'],
        $searchable_field = ['payment_method_id','logistic_id','order_status_id','logistics_status_id','call_method_id','order_status','logistics_status','shipping_code','call_method','order_code','customer_info','customer_address'];

    function get_datatable_v1($params = [])
    {
        $select = [];
        $join = [];
        $where = [];
        $ordering = 'ORDER BY created_at ASC';


        if(!isset($params['order_status_id'])) $params['order_status_id'] = 7;
        if(!isset($params['logistics_status_id'])) $params['logistics_status_id'] = 1;

        if(
            isset($params['date_start']) && !empty($params['date_start'])
        ) $params['date_start'] = $this->db->escape($params['date_start'].' 00:00:00');
        if(
            isset($params['date_end']) && !empty($params['date_end'])
        ) $params['date_end'] = $this->db->escape($params['date_end'].' 23:59:59');

        foreach ($params as $key => $value) {
            if(!in_array($key, ['date_start', 'date_end', 'filter_cs_id'])) $where[] = "a.$key = $value";
            else if($key == 'filter_cs_id' && $value != 0) $where[] = "d.user_id = $value";
        }

        // filter tanggal sale dan tampilkan
        $join[] = "LEFT JOIN orders_process j ON a.order_id = j.order_id and j.order_status_id = 7";
        $select[] = 'j.created_at AS sale_date';
        $where[] = "j.created_at BETWEEN ".$params['date_start'] ." AND ". $params['date_end'];

        // select nama cs yg berhasil sale
        $join[] = "LEFT JOIN (SELECT
            z.order_id, z.order_status_id, z.user_id, zo.username
            FROM orders_process z
            LEFT JOIN sso_user zo ON z.user_id = zo.user_id
            WHERE z.order_status_id = 6
            GROUP BY z.order_id) d ON a.order_id = d.order_id";
        $select[] = 'd.username';

        if(empty($select)) $select = ''; else $select = ", ".implode(", ",$select);
        if(empty($join)) $join = ''; else $join = implode(" \n",$join);
        if(empty($where)) $where = ''; else $where = " AND ".implode(" AND ",$where);

        $sql = "SELECT
                a.*, c.name AS logistic_name,
                (SELECT package_name FROM orders_cart WHERE order_id = a.order_id AND is_package = 1 LIMIT 1) AS package_name $select
            FROM orders a
            LEFT JOIN master_logistics c ON a.logistic_id = c.logistic_id
            $join
            WHERE
            a.version = 1 AND a.is_deleted != 1
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
        return $this->parse_cart_v1($orders_cart);
    }

    function parse_cart_v1($orders_cart = [])
    {
        $orders_cart_package = [];
        foreach ($orders_cart as $key => $value) {
            $key = 'RETAIL';
            $package_name = "Lain-lain";
            if($value->is_package)
            {
                $key = $value->product_package_id;
                $package_name = $value->package_name;
            }

            $orders_cart_package[$key]['info'] = (object) [
                'product_package_id' => $value->product_package_id,
                'package_name' => $package_name,
                'price_type' => $value->price_type,
                'package_price' => $value->package_price
            ];
            $orders_cart_package[$key]['cart'][] = (object) [
                'cart_id' => $value->cart_id,
                'order_id' => $value->order_id,
                'product_id' => $value->product_id,
                'product_merk' => $value->product_merk,
                'product_name' => $value->product_name,
                'price' => $value->price,
                'qty' => $value->qty,
                'weight' => $value->weight,
                'price_type' => $value->price_type,
                'is_package' => $value->is_package
            ];
        }
        return $orders_cart_package;
    }

    function get_byid_v1($id)
    {
        $sql = "SELECT
                a.*, b.icon AS call_method_icon,c.name AS payment_method,
                d.order_invoice_id, d.invoice_number
            FROM orders a
            LEFT JOIN master_call_method b ON a.call_method_id = b.call_method_id
            LEFT JOIN master_payment_method c ON a.payment_method_id = c.payment_method_id
            LEFT JOIN orders_invoices d ON a.order_id = d.order_id
            WHERE a.version = 1 AND a.order_id = ? LIMIT 1";
        return $this->db->query($sql, [$id]);
    }

    function upd($id, $params)
    {
        $this->db->where('order_id', $id);
        return $this->db->update($this->table, $this->_sanity_field($params, $this->fillable_field));
    }
}
