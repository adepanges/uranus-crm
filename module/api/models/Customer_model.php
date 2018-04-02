<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends API_Model {

    function get_by_msisdn($msisdn)
    {
        return $this->db->get_where('customer', ['telephone' => trim($msisdn)])->first_row();
    }

    function get_byid($customer_id)
    {
        return $this->db->get_where('customer', ['customer_id' => $customer_id])->first_row();
    }

    function add($data = [])
    {
        $data = (array) $data;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['status'] = 1;
        $this->db->insert('customer', $this->_sanity_field($data, ['full_name','telephone','created_at','status']));
        return $this->get_byid($this->db->insert_id());
    }

    function upd($data = [], $id = 0)
    {
        $data = (array) $data;
        $data['status'] = 1;
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('customer_id', $id);
        return $this->db->update('customer', $this->_sanity_field($data, ['full_name','telephone','created_at','status']));
    }


    function address_add($data = [])
    {
        $data = (array) $data;

        $sql = "INSERT INTO customer_address (
                    customer_id,
                    address,
                    postal_code,
                    created_at,
                    status,
                    provinsi_id,
                    kabupaten_id,
                    kecamatan_id,
                    desa_id,
                    provinsi,
                    kabupaten,
                    kecamatan,
                    desa_kelurahan
                )
                SELECT
            	? AS customer_id,
            	? AS address,
            	? AS postal_code,
            	? AS created_at,
                ? AS status,
            	a.id AS provinsi_id,
            	b.id AS kabupaten_id,
            	c.id AS kecamatan_id,
            	d.id AS desa_id,
                a.name AS provinsi,
                b.name AS kabupaten,
                c.name AS kecamatan,
                d.name AS desa_kelurahan

            from master_wilayah_provinsi a
            left join master_wilayah_kabupaten b on a.id = b.provinsi_id and b.id = ?
            LEFT JOIN master_wilayah_kecamatan c ON b.id = c.kabupaten_id AND c.id = ?
            LEFT JOIN master_wilayah_desa d ON c.id = d.kecamatan_id AND d.id = ?
            where a.id = ?
            limit 1";

        $this->db->query($sql, [
            'customer_id' => isset($data['customer_id'])?$data['customer_id']:0,
            'address' => isset($data['address'])?$data['address']:'',
            'postal_code' => isset($data['postal_code'])?$data['postal_code']:'',
            'created_at' => date('Y-m-d H:i:s'),
            'status' => 1,
            'kabupaten_id' => (string) isset($data['kabupaten_id'])?$data['kabupaten_id']:'',
            'kecamatan_id' => (string) isset($data['kecamatan_id'])?$data['kecamatan_id']:'',
            'desa_id' => (string) isset($data['desa_id'])?$data['desa_id']:'',
            'provinsi_id' => (string) isset($data['provinsi_id'])?$data['provinsi_id']:''
        ]);
        return $this->db->get_where('customer_address', ['customer_address_id' => $this->db->insert_id()])->first_row();
    }
}
