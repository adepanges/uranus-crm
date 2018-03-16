<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Network_model extends Management_Model {
    protected
        $datatable_param = NULL,
        $table = 'network',
        $orderable_field = ['name','status'],
        $fillable_field = ['name','catch','status'],
        $searchable_field = ['name'];

    function get_datatable()
    {
        $sql = "SELECT * FROM network";

        $sql = $this->_combine_datatable_param($sql);
        $sql_count = $this->_combine_datatable_param($sql, TRUE);
        return [
            'row' => $this->db->query($sql)->result(),
            'total' => $this->db->query($sql_count)->row()->count
        ];
    }

    function get_byid($id)
    {
        return $this->db->where('network_id', $id)->limit(1)->get($this->table)->first_row();
    }

    function del($id)
    {
        return $this->db->delete($this->table, ['network_id' => ((int) $id)]);
    }

    function upd($data, $id)
    {
        $this->db->where('network_id', $id);
        return $this->db->update($this->table, $this->_sanity_field($data));
    }

    function add($data)
    {
        return $this->db->insert($this->table, $this->_sanity_field($data));
    }
}
