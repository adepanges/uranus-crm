<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_model extends SSO_Model {
    function get_active($is_admin)
    {
        if(!$is_admin)
        {
            $this->db->where_not_in('role_id', [1,2]);
        }
        return $this->db->where('status', 1)->get('sso_role');
    }
}
