<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends Management_Model {
    protected
        $datatable_param = NULL,
        $table = 'product',
        $orderable_field = ['merk','name','weight','price','status'],
        $fillable_field = ['code','merk','name','weight','price','status'],
        $searchable_field = ['name'];

    function get_datatable()
    {
        $sql = "SELECT * FROM product";

        $sql = $this->_combine_datatable_param($sql);
        $sql_count = $this->_combine_datatable_param($sql, TRUE);
        return [
            'row' => $this->db->query($sql)->result(),
            'total' => $this->db->query($sql_count)->row()->count
        ];
    }

    function get_byid($id)
    {
        return $this->db->where('product_id', $id)->limit(1)->get($this->table)->first_row();
    }

    function del($id)
    {
        return $this->db->delete($this->table, ['product_id' => ((int) $id)]);
    }

    function upd($data, $id)
    {
        $this->db->where('product_id', $id);
        $res1 = $this->db->update($this->table, $this->_sanity_field($data));

        $this->db->where('product_id', $id);
        $res2 = $this->db->update('product_package_list', $this->_sanity_field($data, ['merk','name','weight']));
        return $res1;
    }

    function add($data)
    {
        return $this->db->insert($this->table, $this->_sanity_field($data));
    }
}
