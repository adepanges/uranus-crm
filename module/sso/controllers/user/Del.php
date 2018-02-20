<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Del extends SSO_Controller {
    public function index($id = 0)
    {
        $this->_restrict_access('sso_users_del', 'rest');

        $user_id = (int) $id;
        if(!$user_id) $this->_response_json([
            'status' => 0,
            'message' => 'id must be set in uri'
        ]);

        $this->load->model('user_model');

        if($this->user_model->del($user_id))
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
