<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends SSO_Model {
    protected
        $datatable_param = NULL,
        $table = 'sso_user',
        $orderable_field = ['username','email','first_name','last_name','status'],
        $fillable_field = ['username','password','email','first_name','last_name','created_at','updated_at','status'],
        $searchable_field = ['username','email','first_name','last_name'];

    function get_byid($user_id)
    {
        return $this->db->where('user_id', ((int) $id))->get($this->table)->row();
    }

    function login_validate($params)
    {
        $sql = "SELECT *
        FROM {$this->table}
        WHERE (username = ? OR email = ?)
        AND password = ?";
        return $this->db->query($sql, [
            $params['username_email'], $params['username_email'], md5($params['password'])
        ]);
    }

    function get_role_by_userid($user_id)
    {
        $sql = "SELECT
            a.*, b.name as role_name,
            b.label as role_label,
            c.name as franchise_name
        FROM sso_user_role a
        LEFT JOIN sso_role b ON a.role_id = b.role_id
        LEFT JOIN franchise c ON a.franchise_id = c.franchise_id
        WHERE
            a.user_id = ?
            AND a.status = 1 AND b.status = 1 AND c.status = 1
        ORDER BY a.is_primary DESC, a.user_role_id ASC";
        return $this->db->query($sql, [
            $user_id
        ]);
    }

    function get_all_access_by_roleid($role_id)
    {
        $sql = "SELECT
            a.module_id, a.link AS module_link, a.name AS module_name,
            b.menu_id, b.name AS menu_name, b.link AS menu_link,
            c.feature_id, c.name AS feature_name, c.label AS feature_label, c.is_menu,
            IF(d.role_access_id IS NOT NULL, 1, 0) AS feature_accessable
        FROM modules a
        LEFT JOIN module_menu b ON a.module_id = b.module_id
        LEFT JOIN module_feature c ON b.menu_id = c.menu_id
        LEFT JOIN sso_role_access d ON d.role_id = ? AND c.feature_id = d.feature_id AND d.status = 1
        WHERE a.status = 1 AND b.status = 1 AND c.status = 1";
        return $this->db->query($sql, [ (int) $role_id ]);
    }
}
