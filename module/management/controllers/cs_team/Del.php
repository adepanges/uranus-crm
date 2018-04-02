<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Del extends Management_Controller {
    public function index($id = 0)
    {
        $this->_restrict_access('management_cs_team_del', 'rest');

        $team_cs_id = (int) $id;
        if(!$team_cs_id) $this->_response_json([
            'status' => 0,
            'message' => 'id must be set in uri'
        ]);

        $this->load->model('cs_team_model');

        if($this->cs_team_model->del($team_cs_id))
        {
            $this->_response_json([
                'status' => 1,
                'message' => 'Berhasil menghapus data'
            ]);
        }
        else
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Gagal menghapus data'
            ]);
        }
    }
}
