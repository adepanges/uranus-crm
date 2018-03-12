<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends SSO_Controller {
	public function index($user_id = 0)
	{
        $this->_restrict_access('sso_users_role_set');
        if(!$user_id) redirect('user');

        $this->load->model(['user_model','role_model']);

        $data = $this->user_model->get_byid($user_id);
        if(empty($data)) redirect('user');

        $is_admin = ($this->data['role_active']->role_id == 1);

        $role_active = $this->role_model->get_active($is_admin)->result();

        $this->_set_data([
            'title' => 'Set Role User',
            'user' => $data,
            'active_role' => $role_active
        ]);
        $this->blade->view('inc/sso/user/role', $this->data);
	}

    function get($id = 0)
    {
        $this->_restrict_access('sso_users_role_list', 'rest');
        $user_id = (int) $id;

        $this->load->model('user_role_model');
        $this->user_role_model->set_datatable_param($this->_datatable_param());
        $role_data = $this->user_role_model->get_datatable($user_id);

        $this->_response_json([
            'recordsFiltered' => $role_data['total'],
            'data' => $role_data['row']
        ]);
    }

    function add()
    {
        $this->_restrict_access('sso_users_role_add', 'rest');
        $params = [
            'user_id' => (int) $this->input->post('user_id'),
            'role_id' => (int) $this->input->post('role_id'),
            'franchise_id' => (int) $this->input->post('franchise_id')
        ];

        if(
            empty($params['user_id']) ||
            empty($params['role_id']) ||
            empty($params['franchise_id'])
        ) $this->_response_json([
            'status' => 0,
            'message' => 'Data tidak lengkap'
        ]);

        $this->load->model('user_role_model');
        $check = $this->user_role_model->get($params);

        $res = TRUE;
        if($check->num_rows())
        {
            // exists
            $row = $check->first_row();
            if($row->status != 1)
            {
                // update if status is inactive
                $res = $this->user_role_model->upd([
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ], $row->user_role_id);
            }
        }
        else
        {
            // add
            $params['created_at'] = date('Y-m-d H:i:s');
            $params['status'] = 1;
            $res = $this->user_role_model->add($params);
        }

        if($res)
        {
            $this->_response_json([
                'status' => 1,
                'message' => 'Berhasil disimpan'
            ]);
        }
        else
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Gagal disimpan'
            ]);
        }
    }

    function del($user_role_id = 0)
    {
        $this->_restrict_access('sso_users_role_add', 'rest');
        $user_role_id = (int) $user_role_id;

        $this->load->model('user_role_model');
        $res = $this->user_role_model->del($user_role_id);

        if($res)
        {
            $this->_response_json([
                'status' => 1,
                'message' => 'Role berhasil dihapus'
            ]);
        }
        else
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Role gagal dihapus'
            ]);
        }
    }
}
