<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log extends SSO_Controller {
    public function index()
    {
        $this->in();
    }

	public function in()
	{
        if($this->_is_sso_signed()) redirect();

        $rand_str = random_string(15);
        $this->_set_data([
            'error_message' => $this->session->userdata('error_message'),
            'auth_access_key' => $rand_str
        ]);
        $session_data = [
            'auth_access_key' => $rand_str,
            'error_message' => ''
        ];
        $this->session->set_userdata($session_data);
        $this->blade->view('inc/sso/auth/login', $this->data);
	}

    public function out()
	{
        $this->session->sess_destroy();
        redirect('auth/log/in');
	}

    public function validate($channel = '')
    {
        if($this->_is_sso_signed()) redirect();
        $this->_validate_access_key();

        switch ($channel) {
            case 'web':
                # web channel
                $this->_validate_web();
                break;

            default:
                redirect();
                break;
        }
    }

    protected function _validate_web()
    {
        $this->load->model('auth_model');
        $params = [
            'username_email' => trim($this->input->post('username')),
            'password' => trim($this->input->post('password'))
        ];
        $res = $this->auth_model->login_validate($params);

        if($res->num_rows())
        {
            $profile = $res->first_row();

            if(isset($profile->status) && $profile->status == 1)
            {
                $this->session->set_userdata([
                    'profile' => (array) $profile,
                    'sso' => 1
                ]);
                redirect('auth/init');
            }
            else
            {
                $this->session->set_userdata('error_message', 'Akun Anda diblokir, silahkan hubungi atasan Anda');
                redirect('auth/log/in');
            }

        }
        else
        {
            $this->session->set_userdata('error_message', 'Username / Email / Password yang anda masukan salah');
            redirect('auth/log/in');
        }
    }

    protected function _validate_access_key()
    {
        $client_auth_access_key = trim($this->input->post('auth_access_key'));
        $server_auth_access_key = trim($this->session->userdata('auth_access_key'));

        $this->session->set_userdata('auth_access_key', '');

        if(empty($client_auth_access_key))
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Missing auth access key'
            ]);
        }

        if($client_auth_access_key !== $server_auth_access_key)
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Unknown auth access key'
            ]);
        }
    }
}
