<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends Portal_Controller {

	public function index()
	{
        $this->load->model('cs_model');

        $tl = $this->session->userdata('tim_leader');
        $team_cs_id = 0;
        if(!empty($tl) && isset($tl->team_cs_id))
        {
            $team_cs_id = $tl->team_cs_id;
        }

        $this->_set_data([
            'title' => 'Portal',
            'list_module' => $this->session->userdata('module'),
            'cs' => $this->cs_model->get_active([
                'role_id' => $this->role_active['role_id'],
                'team_cs_id' => $team_cs_id
            ])->result()
        ]);

        $this->blade->view('inc/portal/app', $this->data);
	}
}
