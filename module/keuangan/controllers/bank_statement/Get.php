<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Get extends Keuangan_Controller {

    protected function _init_params()
    {
        $params = [
            'franchise_id' => $this->franchise->franchise_id,
            'date_start' => $this->input->post('date_start'),
            'date_end' => $this->input->post('date_end')
        ];

        $params['date_start'] = !empty($params['date_start'])?$params['date_start']:date('Y-m-01');
        $params['date_end'] = !empty($params['date_end'])?$params['date_end']:date('Y-m-d');

        // validasi filter tidak lebih dari 31 hari
        $start = strtotime($params['date_start']);
        $end = strtotime($params['date_end']);
        $diff = ($end - $start) / (3600 * 24);

        if($diff > 30)
        {
            $this->_response_json([
                'message' => 'Jarak tanggal maksimal 31 hari'
            ]);
        } else if($diff < 0)
        {
            $this->_response_json([
                'message' => 'Tanggal akhir harus lebih besar dari tanggal mulai'
            ]);
        }

        return $params;
    }

	public function bca()
	{
        $this->_restrict_access('account_statement_list', 'rest');
        $this->load->model('bank_statement_model');

        $params = $this->_init_params();

        $data = $this->bank_statement_model->get_bca($params);

        $balance_before = 0;
        $sequence_before = 0;

        if(
            !empty($data) &&
            isset($data[0]) &&
            isset($data[0]->account_statement_seq))
        {
            $sequence_before = $this->bank_statement_model->get_sequence_smallest($this->franchise->franchise_id, 2, $data[0]->account_statement_seq);
            $balance_before = $this->bank_statement_model->get_balance_before($this->franchise->franchise_id, 2, $data[0]->account_statement_seq);
        }

        $this->_response_json([
            'data' => $data,
            'balance_before' => $balance_before,
            'sequence_before' => $sequence_before
        ]);
	}

    public function bri()
	{
        $this->_restrict_access('account_statement_list', 'rest');
        $this->load->model('bank_statement_model');

        $params = $this->_init_params();

        $data = $this->bank_statement_model->get_bri($params);

        $balance_before = 0;
        $sequence_before = 0;

        if(
            !empty($data) &&
            isset($data[0]) &&
            isset($data[0]->account_statement_seq))
        {
            $sequence_before = $this->bank_statement_model->get_sequence_smallest($this->franchise->franchise_id, 3, $data[0]->account_statement_seq);
            $balance_before = $this->bank_statement_model->get_balance_before($this->franchise->franchise_id, 3, $data[0]->account_statement_seq);
        }

        $this->_response_json([
            'data' => $data,
            'balance_before' => $balance_before,
            'sequence_before' => $sequence_before
        ]);
	}

    public function mandiri()
	{
        $this->_restrict_access('account_statement_list', 'rest');
        $this->load->model('bank_statement_model');

        $params = $this->_init_params();

        $data = $this->bank_statement_model->get_mandiri($params);

        $balance_before = 0;
        $sequence_before = 0;

        if(
            !empty($data) &&
            isset($data[0]) &&
            isset($data[0]->account_statement_seq))
        {
            $sequence_before = $this->bank_statement_model->get_sequence_smallest($this->franchise->franchise_id, 4, $data[0]->account_statement_seq);
            $balance_before = $this->bank_statement_model->get_balance_before($this->franchise->franchise_id, 4, $data[0]->account_statement_seq);
        }

        $this->_response_json([
            'data' => $data,
            'balance_before' => $balance_before,
            'sequence_before' => $sequence_before
        ]);
	}
}
