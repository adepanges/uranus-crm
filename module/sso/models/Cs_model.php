<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cs_model extends SSO_Model {

    function is_leader_cs($user_id)
    {
        return $this->db->get_where('management_team_cs', [
            'leader_id' => $user_id
        ]);
    }

    function set_leader_cs_role_above($user_id)
    {
        return $this->db->query("UPDATE management_team_cs SET
                leader_id = (SELECT a.user_id FROM sso_user a
                    LEFT JOIN sso_user_role b ON a.user_id = b.user_id AND b.role_id IN (1,2)
                    WHERE a.status = 1 AND b.role_id IS NOT NULL
                    ORDER BY b.role_id DESC LIMIT 1
                ),
                updated_at = NOW()
                WHERE leader_id = ?", [$user_id]);
    }

    // function /
}
