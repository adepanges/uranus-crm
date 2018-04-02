<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends SSO_Controller {
	public function index()
	{
        $this->_restrict_access('sso_users');
        $this->load->model('role_model');

        $role_active = $this->role_model->get_active(true)->result();
        
        $this->_set_data([
            'title' => 'Management User',
            'active_role' => $role_active
        ]);
        $this->blade->view('inc/sso/user/app', $this->data);
	}

    public function save()
    {
        $user_id = (int) $this->input->post('user_id');
        if($user_id) $this->_restrict_access('sso_users_upd', 'rest');
        else $this->_restrict_access('sso_users_add', 'rest');

        $data = [
            'username' => $this->input->post('username'),
            'password' => $this->input->post('password'),
            'email' => $this->input->post('email'),
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'status' => (int) $this->input->post('status'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if(is_valid_md5($data['password'])) unset($data['password']);
        else $data['password'] = md5(trim($data['password']));

        $this->load->model('user_model');
        if($this->user_model->check_unique_data([
            'username' => $data['username'],
        ], $user_id) > 0)
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Username yang anda masukan sudah digunakan user lain'
            ]);
        }

        if($this->user_model->check_unique_data([
            'email' => $data['email'],
        ], $user_id) > 0)
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Email yang anda masukan sudah digunakan user lain'
            ]);
        }

        if(!$user_id)
        {
            // tambah
            $data['created_at'] = date('Y-m-d H:i:s');
            $res = $this->user_model->add($data);
        }
        else
        {
            // ubah
            $res = $this->user_model->upd($data, $user_id);
        }

        if($res)
        {
            $this->_response_json([
                'status' => 1,
                'message' => 'Berhasil menyimpan data'
            ]);
        }
        else
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Gagal menyimpan data'
            ]);
        }
    }
}
