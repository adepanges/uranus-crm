<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logistics_process_model extends Penjualan_Model {
    protected
        $datatable_param = NULL,
        $table = 'orders_logistics',
        $fillable_field = ['order_id','user_id','logistics_status_id','status','notes','created_at'];

    function add($params)
    {
        return $this->db->insert($this->table, $this->_sanity_field($params, $this->fillable_field));
    }

    function get($id)
    {
        return $this->db->query('SELECT a.*, concat(b.first_name," ",b.last_name) as full_name
            FROM orders_logistics a
            LEFT JOIN sso_user b ON a.user_id = b.user_id
            WHERE a.order_id = ?
            ORDER BY created_at DESC', [$id]);
    }
}
