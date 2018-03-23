<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Package_product_list_model extends Management_Model {
    protected
        $datatable_param = NULL,
        $table = 'product_package_list',
        $orderable_field = ['name','status'],
        $fillable_field = ['product_package_id','product_id','merk','merk','name','qty','weight','price','status'],
        $searchable_field = ['merk','merk','name','qty'];

    function get_datatable($product_package_id = 0)
    {
        $product_package_id = (int) $product_package_id;
        $sql = "SELECT * FROM product_package_list WHERE product_package_id = $product_package_id";

        $sql = $this->_combine_datatable_param($sql);
        $sql_count = $this->_combine_datatable_param($sql, TRUE);
        return [
            'row' => $this->db->query($sql)->result(),
            'total' => $this->db->query($sql_count)->row()->count
        ];
    }

    function get_byid($id)
    {
        return $this->db->where('product_package_list_id', $id)->limit(1)->get($this->table)->first_row();
    }

    function del($id)
    {
        return $this->db->delete($this->table, ['product_package_list_id' => ((int) $id)]);
    }

    function upd($data, $id)
    {
        $this->db->where('product_package_list_id', $id);
        return $this->db->update($this->table, $this->_sanity_field($data));
    }

    function add($data)
    {
        return $this->db->insert($this->table, $this->_sanity_field($data));
    }
}
