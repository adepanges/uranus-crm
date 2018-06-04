<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cs_team_member_model extends Penjualan_Model {
    protected
        $datatable_param = NULL,
        $orderable_field = ['name','franchise_name','franchise_name','jumlah_cs','status'];

    function get_datatable($id)
    {
        $sql = "SELECT a.*, CONCAT(b.first_name,' ',b.last_name) as username
            FROM management_team_cs_member a
            LEFT JOIN sso_user b ON a.user_id = b.user_id AND b.status = 1
            WHERE a.team_cs_id = $id";

        $sql = $this->_combine_datatable_param($sql);
        $sql_count = $this->_combine_datatable_param($sql, TRUE);
        return [
            'row' => $this->db->query($sql)->result(),
            'total' => $this->db->query($sql_count)->row()->count
        ];
    }
}
