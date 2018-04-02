<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cs_team_member_model extends Management_Model {
    protected
        $datatable_param = NULL,
        $table = 'management_team_cs_member',
        $orderable_field = ['name','franchise_name','franchise_name','jumlah_cs','status'],
        $fillable_field = ['team_cs_id','user_id','created_at','status'],
        $searchable_field = ['name','franchise_name','franchise_name'];

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

    function del($id)
    {
        return $this->db->delete($this->table, ['team_cs_member_id' => ((int) $id)]);
    }

    function upd($data, $id)
    {
        $this->db->where('team_cs_member_id', $id);
        return $this->db->update($this->table, $this->_sanity_field($data));
    }

    function add($data)
    {
        return $this->db->insert($this->table, $this->_sanity_field($data));
    }

    function get($params)
    {
        return $this->db->where($params)->get($this->table);
    }
}
