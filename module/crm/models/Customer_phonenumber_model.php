<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_phonenumber_model extends Management_Model {
    protected
        $datatable_param = NULL,
        $table = 'customer_phonenumber',
        $orderable_field = ['customer_id','phonenumber','is_verified','is_primary','created_at','updated_at'],
        $fillable_field = ['customer_id','phonenumber','is_verified','is_primary','created_at','updated_at'],
        $searchable_field = ['phonenumber'];

    function get_byid($id)
    {
        return $this->db->where('customer_phonenumber_id', $id)->limit(1)->get($this->table)->first_row();
    }

    function get_by_phone($phone = '')
    {
        return $this->db->query("SELECT * FROM customer_phonenumber WHERE phonenumber = ?", [
            trim($phone)
        ]);
    }

    function del($id)
    {
        return $this->db->delete($this->table, ['customer_phonenumber_id' => ((int) $id)]);
    }

    function upd($data, $id)
    {
        $this->db->where('customer_phonenumber_id', $id);
        $res1 = $this->db->update($this->table, $this->_sanity_field($data));
        return $res1;
    }

    function upd_where($data, $where)
    {
        $this->db->where($where);
        $res1 = $this->db->update($this->table, $this->_sanity_field($data));
        return $res1;
    }

    function add($data)
    {
        return $this->db->insert($this->table, $this->_sanity_field($data));
    }
}
