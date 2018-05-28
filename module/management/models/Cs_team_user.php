<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cs_team_user extends Management_Model {
    protected
        $datatable_param = NULL,
        $table = 'sso_user',
        $orderable_field = ['full_name','email'],
        $searchable_field = ['full_name','email'];

    function get_datatable()
    {
        $sql_raw = "SELECT b.team_cs_id,
                a.user_id, CONCAT(a.first_name,' ',a.last_name) AS full_name, a.email
            FROM sso_user a
            LEFT JOIN management_team_cs_member b ON a.user_id = b.user_id
            LEFT JOIN sso_user_role c ON a.user_id = c.user_id
            WHERE c.role_id = 5 AND b.team_cs_id IS NULL";

        $sql = $this->_combine_datatable_param($sql_raw);
        $sql_count = $this->_combine_datatable_param($sql_raw, TRUE);
        return [
            'row' => $this->db->query($sql)->result(),
            'total' => $this->db->query($sql_count)->row()->count
        ];
    }
}
