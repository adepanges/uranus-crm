<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Franchise_model extends Management_Model {
    protected
        $datatable_param = NULL,
        $table = 'franchise',
        $orderable_field = ['code','name','status'],
        $fillable_field = ['code','name','nama_badan','address','tax_number','status'],
        $searchable_field = ['code','name'];

    function get_datatable()
    {
        $sql = "SELECT * FROM {$this->table}";

        $sql = $this->_combine_datatable_param($sql);
        $sql_count = $this->_combine_datatable_param($sql, TRUE);
        return [
            'row' => $this->db->query($sql)->result(),
            'total' => $this->db->query($sql_count)->row()->count
        ];
    }

    function get_byid($id)
    {
        return $this->db->where('franchise_id', $id)->limit(1)->get($this->table)->first_row();
    }

    function del($id)
    {
        return $this->db->delete($this->table, ['franchise_id' => ((int) $id)]);
    }

    function upd($data, $id)
    {
        $this->db->where('franchise_id', $id);
        $res1 = $this->db->update($this->table, $this->_sanity_field($data));
        return $res1;
    }

    function add($data)
    {
        return $this->db->insert($this->table, $this->_sanity_field($data));
    }
}
