<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Init extends SSO_Controller {
    public function index($q = '')
    {
        if(!$this->_is_sso_signed()) redirect('auth/log/in');
        $this->load->model('auth_model');

        $profile = $this->session->userdata('profile');

        $role = [];
        $role_access = [];
        $role_active = [];
        $role_active_access = [];

        $module = [];
        $menu = [];

        $role = $this->auth_model->get_role_by_userid($profile['user_id'])->result_array();
        $role_active = isset($role[0])?$role[0]:[];

        foreach ($role as $key => $value) {
            $role_access[$value['role_name']] = $this->auth_model->get_all_access_by_roleid($value['role_id'])->result_array();
        }

        $role_active_access = isset($role_access[$role_active['role_name']])?$role_access[$role_active['role_name']]:[];

        foreach ($role_active_access as $key => $value) {

            if($value['is_menu'] == 1 && $value['feature_accessable'] == 1)
            {
                $module[$value['module_link']] = [
                    'module_id' => $value['module_id'],
                    'module_link' => $value['module_link'],
                    'module_name' => $value['module_name'],
                ];

                $menu[$value['module_link']] = [
                    'menu_id' => $value['menu_id'],
                    'menu_name' => $value['menu_name'],
                    'menu_link' => $value['menu_link']
                ];
            }
        }

        $this->session->set_userdata([
            'role' => $role,
            'role_access' => $role_access,
            'role_active' => $role_active,
            'role_active_access' => $role_active_access,
            'module' => $module,
            'menu' => $menu
        ]);

        redirect($this->config->item('portal_link'));
    }
}
