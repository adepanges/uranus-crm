<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cs_team_model extends Management_Model {
    protected
        $datatable_param = NULL,
        $table = 'management_team_cs',
        $orderable_field = ['name','franchise_name','franchise_name','jumlah_cs','status'],
        $fillable_field = ['team_cs_id','franchise_id','leader_id','name','desc','status','created_at','updated_at'],
        $searchable_field = ['name','franchise_name','franchise_name'];

    function get_datatable()
    {
        $sql = "SELECT
            a.*, b.name AS franchise_name, c.username,
            CAST(d.jumlah_cs AS UNSIGNED) as jumlah_cs
        FROM management_team_cs a
        LEFT JOIN franchise b ON a.franchise_id = b.franchise_id
        LEFT JOIN sso_user c ON a.leader_id = c.user_id
        LEFT JOIN (
            SELECT team_cs_id, COUNT(*) AS jumlah_cs FROM management_team_cs_member GROUP BY team_cs_id
        ) d ON a.team_cs_id = d.team_cs_id";

        $sql = $this->_combine_datatable_param($sql);
        $sql_count = $this->_combine_datatable_param($sql, TRUE);
        return [
            'row' => $this->db->query($sql)->result(),
            'total' => $this->db->query($sql_count)->row()->count
        ];
    }

    function get_byid($id)
    {
        $sql = "SELECT
            a.*, b.name AS franchise_name, c.username
        FROM management_team_cs a
        LEFT JOIN franchise b ON a.franchise_id = b.franchise_id
        LEFT JOIN sso_user c ON a.leader_id = c.user_id
        WHERE a.team_cs_id = ?";
        return $this->db->query($sql, [$id])->row();
    }

    function del($id)
    {
        return $this->db->delete($this->table, ['team_cs_id' => ((int) $id)]);
    }

    function upd($data, $id)
    {
        $this->db->where('team_cs_id', $id);
        return $this->db->update($this->table, $this->_sanity_field($data));
    }

    function add($data)
    {
        return $this->db->insert($this->table, $this->_sanity_field($data));
    }
}
