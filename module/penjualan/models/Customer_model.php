<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends Penjualan_Model {
    protected
        $datatable_param = NULL,
        $table = 'customer',
        $orderable_field = [],
        $fillable_field = ['full_name','telephone'],
        $searchable_field = [];

    function upd($id, $params)
    {
        $this->db->where('customer_id', $id);
        return $this->db->update($this->table, $this->_sanity_field($params, $this->fillable_field));
    }

    function upd_address($id, $params)
    {
        $this->db->where('customer_address_id', $id);
        return $this->db->update('customer_address', $this->_sanity_field($params, ['customer_id','address','provinsi_id','kabupaten_id','kecamatan_id','desa_id','desa_kelurahan','kecamatan','kabupaten','provinsi','postal_code']));
    }
}
