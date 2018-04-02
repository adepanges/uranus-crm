<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_role_model extends SSO_Model {
    protected
        $datatable_param = NULL,
        $table = 'sso_user_role',
        $orderable_field = ['role_label','franchise_name','created_at',],
        $fillable_field = ['user_id','role_id','franchise_id','status','created_at'],
        $searchable_field = ['role_label','franchise_name'];

    function get_datatable($user_id = 0)
    {
        $sql = $this->_combine_datatable_param("SELECT a.*,
            	b.name AS rola_name, b.label AS role_label,
            	c.name AS franchise_name
            FROM sso_user_role a
            LEFT JOIN sso_role b ON a.role_id = b.role_id
            LEFT JOIN franchise c ON a.franchise_id = c.franchise_id
            WHERE a.status = 1 AND a.user_id = {$user_id}");
        $sql_count = $this->_combine_datatable_param($sql, TRUE);
        return [
            'row' => $this->db->query($sql)->result(),
            'total' => $this->db->query($sql_count)->row()->count
        ];
    }

    function del($id)
    {
        return $this->db->delete($this->table, ['user_role_id' => ((int) $id)]);
    }

    function add($data)
    {
        return $this->db->insert($this->table, $this->_sanity_field($data));
    }

    function upd($data, $user_role_id)
    {
        $this->db->where('user_role_id', $user_role_id);
        return $this->db->update($this->table, $this->_sanity_field($data));
    }

    function get($params)
    {
        return $this->db->where($params)->get($this->table);
    }
}
