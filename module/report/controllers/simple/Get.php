<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Get extends Report_Controller {

	public function index()
	{
        $this->_restrict_access('report_simple_list', 'rest');
        $this->load->model('simple_model');

        $params = [
            'date_start' => $this->input->post('date_start'),
            'date_end' => $this->input->post('date_end')
        ];

        $params['date_start'] = !empty($params['date_start'])?$params['date_start']:date('Y-m-1');
        $params['date_end'] = !empty($params['date_end'])?$params['date_end']:date('Y-m-d');

        $this->simple_model->set_datatable_param($this->_datatable_param());
        $data = $this->simple_model->get_datatable($params);

        $this->_response_json([
            'recordsFiltered' => $data['total'],
            'data' => $data['row']
        ]);
	}
}
