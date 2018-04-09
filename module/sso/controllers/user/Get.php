<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Get extends SSO_Controller {

	public function index()
	{
        $this->_restrict_access('sso_users_list', 'rest');
        $this->load->model('user_model');

        $params = [
            'role_id' => (int) $this->input->post('role_id'),
            'from' => $this->input->post('from')
        ];

        $this->user_model->set_datatable_param($this->_datatable_param());
        $user_data = $this->user_model->get_datatable($params);

        $this->_response_json([
            'recordsFiltered' => $user_data['total'],
            'data' => $user_data['row']
        ]);
	}

    public function byid($id = 0)
    {
        $data = (object) [];
        $user_id = (int) $id;

        if($user_id)
        {
            $this->load->model('user_model');
            $data = $this->user_model->get_byid($user_id);
        }
        $this->_response_json($data);
    }
}
