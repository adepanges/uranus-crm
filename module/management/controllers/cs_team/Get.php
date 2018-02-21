<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Get extends Management_Controller {

	public function index()
	{
        $this->_restrict_access('management_cs_team', 'rest');
        $this->load->model('cs_team_model');

        $this->cs_team_model->set_datatable_param($this->_datatable_param());
        $user_data = $this->cs_team_model->get_datatable();

        $this->_response_json([
            'recordsFiltered' => $user_data['total'],
            'data' => $user_data['row']
        ]);
	}

    public function byid($id = 0)
    {
        $data = (object) [];
        $id = (int) $id;

        if($id)
        {
            $this->load->model('cs_team_model');
            $data = $this->cs_team_model->get_byid($id);
        }
        $this->_response_json($data);
    }
}
