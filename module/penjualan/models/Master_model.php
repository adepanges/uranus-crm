<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_model extends Penjualan_Model {

    function logistics()
    {
        return $this->db->get_where('master_logistics', ['status' => 1]);
    }

    function call_method()
    {
        return $this->db->get_where('master_call_method', ['status' => 1]);
    }

    function payment_method()
    {
        return $this->db->get_where('master_payment_method', ['status' => 1]);
    }

    function wilayah_provinsi()
    {
        return $this->db->get_where('master_wilayah_provinsi');
    }

    function wilayah_kabupaten($provinsi_id)
    {
        return $this->db->get_where('master_wilayah_kabupaten', ['provinsi_id' => $provinsi_id]);
    }

    function wilayah_kecamatan($kabupaten_id)
    {
        return $this->db->get_where('master_wilayah_kecamatan', ['kabupaten_id' => $kabupaten_id]);
    }

    function wilayah_desa($kecamatan_id)
    {
        return $this->db->get_where('master_wilayah_desa', ['kecamatan_id' => $kecamatan_id]);
    }

    function order_status($id = 0)
    {
        if($id)
        {
            $this->db->where('order_status_id', $id);
        }
        return $this->db->get_where('master_order_status');
    }
}
