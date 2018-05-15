<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Get extends Keuangan_Controller {

	public function index()
	{
        $this->_restrict_access('account_statement_list', 'rest');
        $this->load->model('account_statement_model');

        $params = [
            'franchise_id' => $this->franchise->franchise_id,
            'date_start' => $this->input->post('date_start'),
            'date_end' => $this->input->post('date_end')
        ];

        $params['date_start'] = !empty($params['date_start'])?$params['date_start']:date('Y-m-01');
        $params['date_end'] = !empty($params['date_end'])?$params['date_end']:date('Y-m-d');

        $this->account_statement_model->set_datatable_param($this->_datatable_param());
        $data = $this->account_statement_model->get_datatable($params);

        $this->_response_json([
            'recordsFiltered' => $data['total'],
            'data' => $data['row']
        ]);
	}

    public function byid($id = 0)
    {
        $this->_restrict_access('account_statement_list', 'rest');
        $data = (object) [];
        $id = (int) $id;

        if($id)
        {
            $this->load->model('account_statement_model');
            $data = $this->account_statement_model->get_byid($id);
        }
        $this->_response_json($data);
    }
}
