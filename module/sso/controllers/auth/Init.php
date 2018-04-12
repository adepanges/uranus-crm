<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Init extends SSO_Controller {
    public function index($q = '', $param_q = '')
    {
        if(!$this->_is_sso_signed()) redirect('auth/log/in');
        $this->load->model(['auth_model','orders_model','team_cs_model']);

        $profile = $this->session->userdata('profile');

        $role = [];
        $role_access = [];
        $role_active = [];
        $role_active_access = [];
        $access_list = [];
        $tim_leader = [];

        $module = [];
        $menu = [];

        $role = $this->auth_model->get_role_by_userid($profile['user_id'])->result_array();

        if(empty($role))
        {
            $this->session->set_userdata([
                'profile' => '',
                'sso' => ''
            ]);
            $this->session->set_userdata('error_message', 'Anda tidak memiliki hak akses apapun');
            redirect('auth/log/in');
        }

        if($q == 'switch_role')
        {
            foreach ($role as $key => $value) {
                if(md5($value['user_role_id']) == $param_q)
                {
                    $role_active = $value;
                }
            }
        }

        if(empty($role_active))
        {
            $role_active = isset($role[0])?$role[0]:[];
        }


        $is_tim_leader = FALSE;
        foreach ($role as $key => $value) {
            if($value['role_id'] == 6)
            {
                $is_tim_leader = TRUE;
            }
        }

        $leader_tim = $this->team_cs_model->get_leader_id($profile['user_id']);

        if(!empty($leader_tim->first_row()))
        {
            $tim_leader = $leader_tim->first_row();
        }

        foreach ($role as $key => $value) {
            $role_access[$value['role_name']] = $this->auth_model->get_all_access_by_roleid($value['role_id'])->result_array();
        }

        $role_active_access = isset($role_access[$role_active['role_name']])?$role_access[$role_active['role_name']]:[];

        foreach ($role_active_access as $key => $value) {
            $access_list[$value['feature_name']] = (int) $value['feature_accessable'];

            if($value['is_menu'] == 1 && $value['feature_accessable'] == 1)
            {
                $module[$value['module_link']] = [
                    'module_id' => $value['module_id'],
                    'module_link' => $value['module_link'],
                    'module_name' => $value['module_name'],
                ];

                $menu[$value['module_link']][] = [
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
            'access_list' => $access_list,
            'module' => $module,
            'menu' => $menu,
            'tim_leader' => $tim_leader
        ]);

        $orders = $this->orders_model->get_follow_up_by_userid($profile['user_id']);

        if($orders->num_rows())
        {
            $this->session->set_userdata('orders_follow_up', (array) $orders->first_row());
            redirect($this->config->item('penjualan_link'));
            exit;
        }

        if (count($module) == 1) {
            foreach ($module as  $key => $value) {
                redirect(base_url($key));
                exit;
            }
        }
        else
        {
            redirect($this->config->item('portal_link'));
        }
    }
}
