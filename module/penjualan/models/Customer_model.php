<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends Penjualan_Model {
    protected
        $datatable_param = NULL,
        $table = 'customer',
        $orderable_field = [],
        $fillable_field = ['full_name','created_at'],
        $fillable_field_address = ['customer_id','address','provinsi_id','kabupaten_id','kecamatan_id','desa_id','desa_kelurahan','kecamatan','kabupaten','provinsi','postal_code','created_at'],
        $searchable_field = [];

    function add($params)
    {
        return $this->db->insert($this->table, $this->_sanity_field($params, $this->fillable_field));
    }

    function upd($id, $params)
    {
        $this->db->where('customer_id', $id);
        return $this->db->update($this->table, $this->_sanity_field($params, $this->fillable_field));
    }

    function upd_address($id, $params)
    {
        $this->db->where('customer_address_id', $id);
        return $this->db->update('customer_address', $this->_sanity_field($params, $this->fillable_field_address));
    }

    function add_address($params)
    {
        return $this->db->insert('customer_address', $this->_sanity_field($params, $this->fillable_field_address));
    }

    function get_phonenumber_byid($id)
    {
        return $this->db->get_where('customer_phonenumber', [
            'customer_phonenumber_id' => (int) $id
        ]);
    }

    function get_by_phonenumber($phonenumber)
    {
        return $this->db->get_where('customer_phonenumber', [
            'phonenumber' => $phonenumber
        ]);
    }
}
