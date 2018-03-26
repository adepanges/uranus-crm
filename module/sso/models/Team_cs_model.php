<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Team_cs_model extends SSO_Model {

    function get_leader_id($leader_id = 0)
    {
        return $this->db->limit(1)->get_where('management_team_cs', ['status' => 1, 'leader_id' => $leader_id]);
    }
}
