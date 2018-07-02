<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends SSO_Controller {

    function __construct()
    {
        parent::__construct();

    }

	public function index()
	{
        $this->_restrict_access('sso_user_profile');
        $this->load->model(['role_model','user_model']);

        $data_user = $this->user_model->get_byid($this->profile['user_id']);

        if(empty($data_user))
        {
            redirect('auth/log/out');
        }

        $this->_set_data([
            'title' => 'Profile',
            'ref_link' => $this->session->userdata('ref_link'),
            'data_user' => $data_user
        ]);
        $this->session->unset_userdata('ref_link');
        $this->blade->view('inc/sso/user/profile', $this->data);
	}

    public function save()
    {
        $this->_restrict_access('sso_user_profile_save', 'rest');
        $user_id = (int) $this->input->post('user_id');
        $msg = '';

        $data = [
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $data_password = [
            'password_old' => trim($this->input->post('password_old')),
            'password_new' => trim($this->input->post('password_new')),
            'password_confirm' => trim($this->input->post('password_confirm'))
        ];

        // if(is_valid_md5($data['password'])) unset($data['password']);
        // else $data['password'] = md5(trim($data['password']));

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

        if(!empty($data_password['password_new']))
        {
            if(empty($data_password['password_old']))
            {
                $this->_response_json([
                    'status' => 0,
                    'message' => 'Harap isi password lama'
                ]);
            }

            if($data_password['password_new'] == $data_password['password_confirm'])
            {
                $check_password = $this->user_model->check_password($user_id, $data_password['password_old']);

                if(empty($check_password))
                {
                    $this->_response_json([
                        'status' => 0,
                        'message' => 'Password lama salah'
                    ]);
                }

                $res_password = $this->user_model->upd([
                    'password' => md5($data_password['password_new'])
                ], $user_id);

                if($res_password)
                {
                    $msg = ', password berhasil dirubah';
                }
                else
                {
                    $msg = ', password gagal dirubah';
                }
            }
            else
            {
                $this->_response_json([
                    'status' => 0,
                    'message' => 'Password baru tidak sama'
                ]);
            }
        }
        else if(!empty($data_password['password_old']))
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Password baru belum diisi'
            ]);
        }

        // ubah
        $res = $this->user_model->upd($data, $user_id);

        if($res)
        {
            $this->_response_json([
                'status' => 1,
                'message' => 'Berhasil menyimpan data'.$msg
            ]);
        }
        else
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Gagal menyimpan data'.$msg
            ]);
        }
    }

    public function ref($ref_link = ''){
        if(!empty($link))
        {
            $ref_link = base64_decode($ref_link);
            $this->session->set_userdata('ref_link', $ref_link);
        }
        else
        {
            redirect('user/profile');
        }
    }
}
