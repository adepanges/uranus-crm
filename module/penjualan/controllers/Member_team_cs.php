<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_team_cs extends Penjualan_Controller {

	public function get()
	{
        $id = (int) $this->input->post('team_cs_id');

        $this->load->model('Cs_team_member_model');
        $this->Cs_team_member_model->set_datatable_param($this->_datatable_param());
        $data = $this->Cs_team_member_model->get_datatable($id);

        $this->_response_json([
            'recordsFiltered' => $data['total'],
            'data' => $data['row']
        ]);
	}
}
