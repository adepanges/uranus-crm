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
            'logout_link' => $this->config->item('sso_link').'/auth/log/out'
        ];

        // $this->load->library('encryption');
        // $this->encryption->initialize([
        //     'driver' => 'openssl',
        //     'cipher' => 'aes-256',
        //     'mode' => 'cbc',
        // ]);
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

        $params['search'] = trim($params['search']['value']);
        return $params;
    }

    protected function _response_json($resp)
    {
        header('Content-Type: application/json');
        echo json_encode($resp);
        exit;
    }
}
