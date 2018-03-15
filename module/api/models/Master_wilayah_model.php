<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_wilayah_model extends API_Model {
    function provinsi()
    {
        return $this->db->get_where('master_wilayah_provinsi');
    }

    function kabupaten($provinsi_id)
    {
        return $this->db->get_where('master_wilayah_kabupaten', ['provinsi_id' => $provinsi_id]);
    }

    function kecamatan($kabupaten_id)
    {
        return $this->db->get_where('master_wilayah_kecamatan', ['kabupaten_id' => $kabupaten_id]);
    }

    function desa($kecamatan_id)
    {
        return $this->db->get_where('master_wilayah_desa', ['kecamatan_id' => $kecamatan_id]);
    }
}
