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

    function bca()
    {
        $this->get_data(2);
    }

    function bri()
    {
        $this->get_data(3);
    }

    function mandiri()
    {
        $this->get_data(4);
    }

    protected function get_data($payment_method_id)
    {
        $this->_restrict_access('account_statement_list', 'rest');
        $this->load->model('bank_statement_model');

        $params = $this->_init_params();

        $data = $this->bank_statement_model->get_query($payment_method_id, $params);

        $balance_before = 0;
        $sequence_before = 0;

        if(
            !empty($data) &&
            isset($data[0]) &&
            isset($data[0]->account_statement_seq))
        {
            $date = $data[0]->transaction_date;

            $sequence_before = $this->bank_statement_model->get_sequence_smallest($this->franchise->franchise_id, $payment_method_id, $date);

            $balance_before = $this->bank_statement_model->get_balance_before($this->franchise->franchise_id, $payment_method_id, $date);
        }

        $this->_response_json([
            'data' => $data,
            'balance_before' => $balance_before,
            'sequence_before' => $sequence_before
        ]);
    }
}
