<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dermeva_Controller extends CI_Controller {
    protected $data = [];

    function __construct()
    {
        parent::__construct();
        $this->load->helper('uranus');

        $this->data = [
            'title' => $this->config->item('app_name'),
            'profile' => $this->session->userdata('profile'),
            'module_url' => [
                'sso' => $this->config->item('sso_link')
            ],
            'access_list' => (object) $this->session->userdata('access_list'),
            'role_active' => (object) $this->session->userdata('role_active'),
            'franchise' => (object) $this->session->userdata('franchise'),
            'logout_link' => $this->config->item('sso_link').'/auth/log/out'
        ];

        $this->profile = $this->session->userdata('profile');
        $this->role_active = $this->session->userdata('role_active');
        $this->franchise = $this->session->userdata('franchise');

        $this->_check_active_user();

        // $this->load->library('encryption');
        // $this->encryption->initialize([
        //     'driver' => 'openssl',
        //     'cipher' => 'aes-256',
        //     'mode' => 'cbc',
        // ]);

        $role_sess = $this->session->userdata('role');
        $role = [];
        if(!empty($role_sess) && is_array($role_sess))
        {
            foreach ($role_sess as $key => $value) {
                $role[$value['franchise_id']]['franchise_id'] = $value['franchise_id'];
                $role[$value['franchise_id']]['franchise_name'] = $value['franchise_name'];
                $role[$value['franchise_id']]['role'][] = [
                    'chipper_user_role_id' => md5($value['user_role_id']),
                    'role_label' => $value['role_label']
                ];
            }
            $this->data['role'] = $role;
        }
    }

    protected function _sso_profile()
    {
        return $this->session->userdata('profile');
    }

    protected function _is_sso_signed()
    {
        return (!empty($this->_sso_profile()) && $this->session->userdata('sso') == 1);
    }

    protected function _restrict_access($feature_name, $channel = 'web')
    {
        $access_list = $this->session->userdata('access_list');
        $prohibited = TRUE;



        if(is_array($feature_name))
        {
            foreach ($feature_name as $key => $value) {
                if(isset($access_list[$value]) && $access_list[$value] == 1)
                {
                    $prohibited = FALSE;
                }
            }
        }
        else if(isset($access_list[$feature_name]) && $access_list[$feature_name] == 1)
        {
            $prohibited = FALSE;
        }

        if(!$this->_is_sso_signed() || $prohibited)
        {
            switch ($channel) {
                case 'rest':
                    # rest
                    $this->_response_json([
                        'status' => 0,
                        'message' => 'Anda tidak memiliki hak akses'
                    ]);
                    break;

                default:
                    # web
                    $this->blade->view('error/403');
                    break;
            }
            exit;
        }
    }

    protected function _set_data($data)
    {
        if(!empty($data) && is_array($data))
        {
            $this->data = array_merge($this->data, $data);
        }
    }

    protected function _datatable_param()
    {
        $params = [
            'start' => (int) $this->input->post('start'),
            'length' => (int) $this->input->post('length'),
            'columns' => $this->input->post('columns'),
            'order' => $this->input->post('order'),
            'search' => $this->input->post('search')
        ];
        if(isset($params['columns']) && is_array($params['columns']))
        {
            foreach ($params['columns'] as $key => $value) {
                $params['columns'][$key] = $value['data'];
            }
        }

        if(!$params['length']) $params['length'] = 10;

        $column = 'id';
        $dir = 'asc';
        if(is_array($params['order']) && !empty($params['order']))
        {
            if(
                isset($params['order'][0]['column']) &&
                isset($params['order'][0]['dir'])
                )
            {
                $index = (int) $params['order'][0]['column'];
                $column = $params['columns'][$index];
                $dir = $params['order'][0]['dir'];
            }
        }

        $params['order'] = [
            'column' => $column,
            'dir' => $dir
        ];

        if(
            isset($params['search']) &&
            isset($params['search']['value']) &&
            !empty($params['search']['value']))
        {
            $params['search'] = trim($params['search']['value']);
        }
        else
        {
            $params['search'] = '';
        }

        return $params;
    }

    protected function _check_active_user()
    {
        if(!empty($this->profile))
        {
            $res = $this->db->limit(1)->get_where('sso_user', [
                'user_id' => $this->profile['user_id'],
                'status' => 1
            ])->first_row();
            if(empty($res))
            {
                $this->session->set_userdata('profile', []);
                redirect($this->data['logout_link']);
            }
        }
    }

    protected function _response_json($resp)
    {
        if(is_object($resp)) $resp->system_process_time = (microtime(true) - URANUS_LAUNCH);
        else if(is_array($resp)) $resp['system_process_time'] = (microtime(true) - URANUS_LAUNCH);

        header('Content-Type: application/json');
        echo json_encode($resp);

        exit;
    }
}
