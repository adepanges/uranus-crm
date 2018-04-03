<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends SSO_Model {
    protected
        $datatable_param = NULL,
        $table = 'sso_user',
        $orderable_field = ['username','email','first_name','last_name','status'],
        $fillable_field = ['username','password','email','first_name','last_name','created_at','updated_at','status'],
        $searchable_field = ['username','email','first_name','last_name'];

    function get_datatable($params = [])
    {
        $where = [];

        if(isset($params['role_id']) && $params['role_id'] != 0)
        {
            $params['role_id'] = (int) $params['role_id'];
            $where[] = "a.user_id IN (SELECT user_id FROM sso_user_role WHERE role_id = {$params['role_id']})";
        }

        $where[] = "a.user_id <> 1";

        if(!empty($where)) $where = "WHERE ".implode(" AND ", $where);
        else $where = '';

        $sql = "SELECT a.*, (SELECT 1 FROM `sso_user_role` WHERE user_id = a.user_id AND role_id IN (1,2) ORDER BY role_id ASC LIMIT 1) as is_admin_manajer
            FROM {$this->table} a
            $where";


        $sql_user = $this->_combine_datatable_param($sql);
        $sql_count = $this->_combine_datatable_param($sql, TRUE);

        return [
            'row' => $this->db->query($sql_user)->result(),
            'total' => $this->db->query($sql_count)->row()->count
        ];
    }

    function get_byid($id)
    {
        return $this->db->where('user_id', ((int) $id))->get($this->table)->row();
    }

    function del($id)
    {
        return $this->db->delete($this->table, ['user_id' => ((int) $id)]);
    }

    function upd($data, $user_id)
    {
        $this->db->where('user_id', $user_id);
        return $this->db->update($this->table, $this->_sanity_field($data));
    }

    function add($data)
    {
        return $this->db->insert($this->table, $this->_sanity_field($data));
    }

    function check_unique_data($data, $self_id = 0)
    {
        if(!empty($data))
        {
            $this->db->or_where($this->_sanity_field($data, ['username','email']));
            if($self_id) $this->db->where('user_id <>', $self_id);
            $this->db->from($this->table);
            return $this->db->count_all_results();
        }
        else
        {
            return 0;
        }
    }
}
