<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Get extends Portal_Controller {

	public function cs($user_id = 0)
	{
        $user_id = (int) $user_id;
        $this->load->model('statistik_model');

        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');

        $res = $this->statistik_model->cs($user_id, $start_date, $end_date);
        $this->_response_json([
            'data' => $res->result()
        ]);
	}

    public function all()
    {
        $this->load->model('statistik_model');

        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');

        $res = $this->statistik_model->all($start_date, $end_date);
        $this->_response_json([
            'data' => $res->result()
        ]);
    }
}
