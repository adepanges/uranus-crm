<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends Keuangan_Controller {

	public function index()
	{
        $this->_restrict_access('account_statement');
        $this->load->model(['payment_method_model']);

        $this->_set_data([
            'title' => 'Account Statement',
            'account' => $this->payment_method_model->get_active()->result()
        ]);

        $this->blade->view('inc/keuangan/account_statement/app', $this->data);
	}

    public function save()
    {
        $account_statement_id = (int) $this->input->post('account_statement_id');
        if($account_statement_id) $this->_restrict_access('account_statement_upd', 'rest');
        else $this->_restrict_access('account_statement_add', 'rest');

        $this->load->model(['account_statement_model','franchise_model']);

        $trx_date = !empty($this->input->post('transaction_date'))?$this->input->post('transaction_date'):date('Y-m-d');


        $franchise = $this->franchise_model->get_byid($this->franchise->franchise_id)->first_row();

        $data = [
            'franchise_id' => $franchise->franchise_id,
            'payment_method_id' => $this->input->post('payment_method_id'),
            'transaction_type' => 'D',
            'transaction_date' => $trx_date,
            'transaction_amount' => $this->input->post('transaction_amount'),
            'note' => $this->input->post('note'),
            'user_id' => $this->profile['user_id'],
            'updated_at' => date('Y-m-d H:i:s')

        ];

        if(!$account_statement_id)
        {
            $trx_date_unix = strtotime($trx_date);
            $next_seq = $this->account_statement_model->get_next_seq($this->franchise->franchise_id, date('Y', $trx_date_unix));
            $inv_seq_number = str_pad($next_seq, 7, "0", STR_PAD_LEFT);

            $data['seq_invoice'] = $next_seq;
            $data['generated_invoice'] = $franchise->code."/".date('Ymd', $trx_date_unix)."/".$inv_seq_number;
            $data['created_at'] = date('Y-m-d H:i:s');

            $res = $this->account_statement_model->add($data);
        }
        else
        {
            // ubah
            $check = $this->account_statement_model->get_byid($account_statement_id);
            $res = FALSE;
            if(isset($check->commit) && $check->commit != 1)
            {
                $res = $this->account_statement_model->upd($data, $account_statement_id);
            }
        }

        if($res)
        {
            $this->_response_json([
                'status' => 1,
                'message' => 'Berhasil menyimpan data'
            ]);
        }
        else
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Gagal menyimpan data'
            ]);
        }
    }

    function sort_invoice_number()
    {
        $this->_restrict_access('account_statement_sort', 'rest');
        $this->load->model(['account_statement_model','franchise_model']);

        $this->account_statement_model->set_uncommit_to_unfix($this->franchise->franchise_id);
        $res = $this->account_statement_model->get_uncommit($this->franchise->franchise_id)->result();
        $franchise = $this->franchise_model->get_byid($this->franchise->franchise_id)->first_row();

        foreach ($res as $key => $value) {
            $trx_date  = $value->transaction_date;
            $trx_date_unix = strtotime($trx_date);

            $next_seq = $this->account_statement_model->get_next_seq($this->franchise->franchise_id, date('Y', $trx_date_unix), 1);
            $inv_seq_number = str_pad($next_seq, 7, "0", STR_PAD_LEFT);

            $this->account_statement_model->upd([
                'seq_invoice' => $next_seq,
                'generated_invoice' => $franchise->code."/".date('Ymd', $trx_date_unix)."/".$inv_seq_number,
                'fix' => 1
            ], $value->account_statement_id);
        }

        $this->_response_json([
            'status' => 1,
            'message' => 'Berhasil sort nomor invoice'
        ]);
    }

    function commit_invoice_number()
    {
        $this->_restrict_access('account_statement_commit', 'rest');
        $this->load->model(['account_statement_model','franchise_model']);

        $res = $this->account_statement_model->commit_invoice_number($this->franchise->franchise_id);
        if($res)
        {
            $this->_response_json([
                'status' => 1,
                'message' => 'Berhasil commit invoice'
            ]);
        }
        else
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Gagal commit invoice'
            ]);
        }
    }
}
