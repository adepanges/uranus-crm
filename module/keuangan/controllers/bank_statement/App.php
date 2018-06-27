<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends Keuangan_Controller {

    public function fix_sequence($payment_method_id = 0)
    {
        $payment_method_id = (int) $payment_method_id;
        $data_transaction = $this->input->post('transaction');

        if($payment_method_id == 0)
        {
            $this->_response_json([
                'success' => 0,
                'message' => 'Payment_method_id not send in URI'
            ]);
        }

        if(empty($data_transaction)) $data_transaction = '[]';
        $data_transaction = json_decode($data_transaction);
        if(empty($data_transaction))
        {
            $this->_response_json([
                'success' => 0,
                'message' => 'Data transaction tidak sesuai format'
            ]);
        }

        $this->load->model('bank_statement_model');
        $success_count = 0;

        foreach ($data_transaction as $key => $value) {
            $res = $this->bank_statement_model->upd_sequence($value->id, $value->seq, $value->target_seq);
            if($res)
            {
                $success_count++;
            }
        }

        if($success_count == count($data_transaction))
        {
            $this->_response_json([
                'success' => 1,
                'message' => 'Data sequence sudah diperbaiki'
            ]);
        }
        else
        {
            $this->_response_json([
                'success' => 1,
                'message' => 'Data sequence sudah diperbaiki, namun tidak sempurna'
            ]);
        }
	}

    public function fix_balance($payment_method_id = 0)
    {
        $payment_method_id = (int) $payment_method_id;
        $data_transaction = $this->input->post('transaction');

        if($payment_method_id == 0)
        {
            $this->_response_json([
                'success' => 0,
                'message' => 'Payment_method_id not send in URI'
            ]);
        }

        if(empty($data_transaction)) $data_transaction = '[]';
        $data_transaction = json_decode($data_transaction);
        if(empty($data_transaction))
        {
            $this->_response_json([
                'success' => 0,
                'message' => 'Data transaction tidak sesuai format'
            ]);
        }

        $this->load->model('bank_statement_model');
        $success_count = 0;

        foreach ($data_transaction as $key => $value) {
            $transaction = $this->bank_statement_model->get($value->id);

            $res = FALSE;
            if(!empty($transaction))
            {
                $balance_before = $this->bank_statement_model->get_balance_before($this->franchise->franchise_id, $payment_method_id, $transaction->account_statement_seq);
                if($transaction->transaction_type == 'K') $balance = $balance_before + $transaction->transaction_amount;
                else if($transaction->transaction_type == 'D') $balance = $balance_before - $transaction->transaction_amount;
                $res = $this->bank_statement_model->upd_balance($transaction->account_statement_id, $transaction->account_statement_seq, $balance);
            }

            if($res)
            {
                $success_count++;
            }
        }

        if($success_count == count($data_transaction))
        {
            $this->_response_json([
                'success' => 1,
                'message' => 'Data balance sudah diperbaiki'
            ]);
        }
        else
        {
            $this->_response_json([
                'success' => 1,
                'message' => 'Data balance sudah diperbaiki, namun tidak sempurna'
            ]);
        }
	}

    function upd_sequence()
    {
        $patams = [
            'account_statement_id' => (int) $this->input->post('account_statement_id'),
            'account_statement_seq' => (int) $this->input->post('current_account_statement_seq')
        ];
        $target_account_statement_seq = (int) $this->input->post('target_account_statement_seq');

        $this->load->model('bank_statement_model');

        $transaction = $this->bank_statement_model->get($patams['account_statement_id']);
        if(empty($transaction))
        {
            $this->_response_json([
                'success' => 0,
                'message' => 'Transaksi tidak ditemukan'
            ]);
        }

        $res = $this->bank_statement_model->upd_sequence($patams['account_statement_id'], $patams['account_statement_seq'], $target_account_statement_seq);

        if($res)
        {
            $this->_response_json([
                'success' => 1,
                'message' => 'Urutan berasil dirubah'
            ]);
        }
        else
        {
            $this->_response_json([
                'success' => 0,
                'message' => 'Urutan gagal dirubah'
            ]);
        }
    }
}
