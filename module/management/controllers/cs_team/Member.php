<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends Management_Controller {

	public function index($id = 0)
	{
        $this->_restrict_access('management_cs_team_member');
        $id = (int) $id;

        $this->load->model('cs_team_model');
        $cs_team = $this->cs_team_model->get_byid($id);

        if(empty($cs_team)) redirect('cs_team');

        $this->_set_data([
            'title' => 'Management CS Team - Member',
            'cs_team' => $cs_team
        ]);

        $this->blade->view('inc/management/cs_team/member', $this->data);
	}

    function get($id = 0)
    {
        $this->_restrict_access('management_cs_team_member_list', 'rest');
        $id = (int) $id;

        $this->load->model('cs_team_member_model');
        $this->cs_team_member_model->set_datatable_param($this->_datatable_param());
        $member_data = $this->cs_team_member_model->get_datatable($id);

        $this->_response_json([
            'recordsFiltered' => $member_data['total'],
            'data' => $member_data['row']
        ]);
    }

    function add()
    {
        $this->_restrict_access('management_cs_team_member_add', 'rest');
        $params = [
            'team_cs_id' => (int) $this->input->post('team_cs_id'),
            'user_id' => (int) $this->input->post('user_id')
        ];

        if(
            empty($params['team_cs_id']) ||
            empty($params['user_id'])
        ) $this->_response_json([
            'status' => 0,
            'message' => 'Data tidak lengkap'
        ]);

        $this->load->model('cs_team_member_model');
        $check = $this->cs_team_member_model->get($params);

        $res = TRUE;
        if($check->num_rows())
        {
            // exists
            $row = $check->first_row();
            if($row->status != 1)
            {
                // update if status is inactive
                $res = $this->cs_team_member_model->upd([
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ], $row->team_cs_member_id);
            }
        }
        else
        {
            // add
            $params['created_at'] = date('Y-m-d H:i:s');
            $params['status'] = 1;
            $res = $this->cs_team_member_model->add($params);
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

    function del($team_cs_member_id = 0)
    {
        $this->_restrict_access('management_cs_team_member_del', 'rest');
        $team_cs_member_id = (int) $team_cs_member_id;

        $this->load->model('cs_team_member_model');
        $res = $this->cs_team_member_model->del($team_cs_member_id);

        if($res)
        {
            $this->_response_json([
                'status' => 1,
                'message' => 'Member berhasil dihapus'
            ]);
        }
        else
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Member gagal dihapus'
            ]);
        }
    }
}
