<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Get extends Keuangan_Controller {

	public function bca()
	{
        $this->_restrict_access('account_statement_list', 'rest');
        $this->load->model('bank_statement_model');

        $params = [
            'franchise_id' => $this->franchise->franchise_id,
            'date_start' => $this->input->post('date_start'),
            'date_end' => $this->input->post('date_end')
        ];

        $params['date_start'] = !empty($params['date_start'])?$params['date_start']:date('Y-m-01');
        $params['date_end'] = !empty($params['date_end'])?$params['date_end']:date('Y-m-d');

        $data = $this->bank_statement_model->get_bca($params);

        $this->_response_json([
            'data' => $data['row']
        ]);
	}
}
