<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_model extends SSO_Model {
    function get_active()
    {
        return $this->db->where('status', 1)->get('sso_role');
    }
}
