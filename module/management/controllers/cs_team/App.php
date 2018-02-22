<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends Management_Controller {

	public function index()
	{
        $this->_restrict_access('management_cs_team');
        $this->_set_data([
            'title' => 'Management CS Team'
        ]);

        $this->blade->view('inc/management/cs_team/app', $this->data);
	}

    public function save()
    {
        $team_cs_id = (int) $this->input->post('team_cs_id');
        if($user_id) $this->_restrict_access('management_cs_team_upd', 'rest');
        else $this->_restrict_access('management_cs_team_add', 'rest');

        $data = [
            'name' => $this->input->post('name'),
            'franchise_id' => (int) $this->input->post('franchise_id'),
            'leader_id' => (int) $this->input->post('leader_id'),
            'status' => (int) $this->input->post('status'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if($data['leader_id'] == 0) unset($data['leader_id']);

        $this->load->model('cs_team_model');
        if(!$team_cs_id)
        {
            // tambah
            $data['created_at'] = date('Y-m-d H:i:s');
            $res = $this->cs_team_model->add($data);
        }
        else
        {
            // ubah
            $res = $this->cs_team_model->upd($data, $team_cs_id);
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
