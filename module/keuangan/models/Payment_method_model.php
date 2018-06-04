<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_method_model extends Keuangan_Model {

    function get_active()
    {
        return $this->db->get_where('master_payment_method', ['status' => 1]);
    }
}
