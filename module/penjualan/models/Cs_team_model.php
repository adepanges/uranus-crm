<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cs_team_model extends Penjualan_Model {
    function get_active($franchise_id = 0)
    {
        return $this->db->get_where('management_team_cs', [
            'franchise_id' => (int) $franchise_id,
            'status' => 1
        ]);
    }
}
