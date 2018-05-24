<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends Management_Model {
    protected
        $datatable_param = NULL,
        $table = 'customer',
        $orderable_field = ['full_name','email','gender','birthdate','telephone','created_at','updated_at','status'],
        $fillable_field = ['full_name','email','gender','birthdate','telephone','created_at','updated_at','status'],
        $searchable_field = ['full_name','email','telephone'];

    function get_datatable()
    {
        $sql_raw = "SELECT a.*,
            (SELECT kabupaten FROM customer_address WHERE customer_id = a.customer_id AND is_primary = 1 LIMIT 1) as city
        FROM customer a";

        $sql = $this->_combine_datatable_param($sql_raw);
        $sql_count = $this->_combine_datatable_param($sql_raw, TRUE);
        return [
            'row' => $this->db->query($sql)->result(),
            'total' => $this->db->query($sql_count)->row()->count
        ];
    }

    function get_byid($id)
    {
        return $this->db->where('customer_id', $id)->limit(1)->get($this->table)->first_row();
    }

    function del($id)
    {
        return $this->db->delete($this->table, ['customer_id' => ((int) $id)]);
    }

    function upd($data, $id)
    {
        $this->db->where('customer_id', $id);
        $res1 = $this->db->update($this->table, $this->_sanity_field($data));
        return $res1;
    }

    function add($data)
    {
        return $this->db->insert($this->table, $this->_sanity_field($data));
    }
}
