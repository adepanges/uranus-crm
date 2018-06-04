<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Franchise_model extends Keuangan_Model {

    function get_byid($id)
    {
        return $this->db->get_where('franchise', ['franchise_id' => $id]);
    }
}
