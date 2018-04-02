<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Package_model extends Management_Model {
    protected
        $datatable_param = NULL,
        $table = 'product_package',
        $orderable_field = ['code','name','price','price_type','status'],
        $fillable_field = ['code','name','price','price_type','status'],
        $searchable_field = ['code', 'price_type', 'name'];

    function get_datatable()
    {
        $sql = "SELECT a.product_package_id, a.code, a.name, a.status, a.price_type,
            CASE
                WHEN a.price_type = 'PACKAGE'
                    THEN a.price
                WHEN a.price_type = 'RETAIL'
                    THEN (SELECT SUM(price) FROM `product_package_list` WHERE product_package_id = a.product_package_id GROUP BY product_package_id)
            END AS price
            FROM product_package a";

        $sql = $this->_combine_datatable_param($sql);
        $sql_count = $this->_combine_datatable_param($sql, TRUE);
        return [
            'row' => $this->db->query($sql)->result(),
            'total' => $this->db->query($sql_count)->row()->count
        ];
    }

    function get_byid($id)
    {
        return $this->db->where('product_package_id', $id)->limit(1)->get($this->table)->first_row();
    }

    function del($id)
    {
        return $this->db->delete($this->table, ['product_package_id' => ((int) $id)]);
    }

    function upd($data, $id)
    {
        $this->db->where('product_package_id', $id);
        return $this->db->update($this->table, $this->_sanity_field($data));
    }

    function add($data)
    {
        return $this->db->insert($this->table, $this->_sanity_field($data));
    }
}
