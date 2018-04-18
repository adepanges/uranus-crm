<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Franchise_model extends SSO_Model {
    function get_byid($id)
    {
        return $this->db
            ->where('status', 1)
            ->where('franchise_id', $id)
            ->get('franchise')
            ->first_row();
    }
}
