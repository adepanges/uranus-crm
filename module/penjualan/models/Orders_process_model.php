<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders_process_model extends Penjualan_Model {
    protected
        $datatable_param = NULL,
        $table = 'orders_process',
        $fillable_field = ['order_id','user_id','order_status_id','status','notes','event_postback_status','created_at'];

    function add($params)
    {
        return $this->db->insert($this->table, $this->_sanity_field($params, $this->fillable_field));
    }

    function get($id)
    {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get_where($this->table, ['order_id' => $id]);
    }

    function get_by_status($id, $orders_status_id)
    {
        return $this->db->limit(1)->get_where($this->table, ['order_id' => $id, 'order_status_id' => $orders_status_id])->first_row();
    }
}
