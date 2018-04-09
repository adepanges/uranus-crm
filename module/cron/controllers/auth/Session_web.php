<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Session_web extends Cron_Controller {

    function __construct()
    {
        parent::__construct();
        $this->eins_log->init([
            'log_name' => 'session_clear'
        ]);
    }

	public function housekeeping()
	{
        $this->load->model('session_model');
        $this->session_model->clear_web();
        $this->eins_log->write('info', 'CLEAR SESSION', [
            'affected_rows' => $this->db->affected_rows()
        ]);
	}
}
