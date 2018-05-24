<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cs_model extends Portal_Model {

	public function get_active($params = [])
	{
        $where = [];
        if($params['role_id'] == 6 && $params['team_cs_id'] != 0)
        {
            $params['team_cs_id'] = (int) $params['team_cs_id'];
            $where[] = "a.user_id IN (SELECT user_id FROM management_team_cs_member WHERE team_cs_id = {$params['team_cs_id']})";
        }

        if(!empty($where))
        {
            $where = ' AND '.implode(' AND ', $where);
        } else $where = '';

        $sql = "SELECT a.*
            FROM sso_user a
            WHERE
                a.user_id IN (SELECT user_id FROM sso_user_role WHERE role_id = 5) $where
            ORDER BY first_name, first_name ASC";
        return $this->db->query($sql);
	}
}
