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
        $trx_date_unix = strtotime($trx_date);

        $franchise = $this->franchise_model->get_byid($this->franchise->franchise_id)->first_row();

        $last_date_commited_trx = strtotime('2000-01-01');
        $last_commited_trx = $this->account_statement_model->get_last_date_inv($this->franchise->franchise_id)->first_row();
        if(!empty($last_commited_trx) && isset($last_commited_trx->transaction_date))
        {
            $last_date_commited_trx = strtotime($last_commited_trx->transaction_date);
        }

        $data = [
            'franchise_id' => $franchise->franchise_id,
            'payment_method_id' => $this->input->post('payment_method_id'),
            'transaction_type' => $this->input->post('transaction_type'),
            'transaction_date' => $trx_date,
            'transaction_amount' => $this->input->post('transaction_amount'),
            'note' => $this->input->post('note'),
            'user_id' => $this->profile['user_id'],
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if($trx_date_unix < $last_date_commited_trx)
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Gagal menyimpan data, tanggal transaksi harus lebih atau sama dengan urutan terakhir'
            ]);
        }

        if(!$account_statement_id)
        {
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

    function commit_transaction()
    {
        $this->_restrict_access('account_statement_commit', 'rest');
        $this->load->model('account_statement_model');

        // check urutan no invoice
        $this->check_uncommit_invoice_validity();

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

    protected function check_uncommit_invoice_validity()
    {
        $this->load->model('account_statement_model');
        $data = $this->account_statement_model->get_uncommit($this->franchise->franchise_id)->result();
        $next_seq = 1;
        $trx_date_unix = time();

        if(!empty($data) && isset($data[0]))
        {
            $trx_date_unix = strtotime($data[0]->transaction_date);
            $next_seq = (int) $data[0]->seq_invoice;
        }
        $previous_inv_date_unix = $trx_date_unix;

        $previous_inv_rec = $this->account_statement_model->get_last_date_inv($this->franchise->franchise_id)->first_row();
        if(!empty($previous_inv_rec)) $previous_inv_date_unix = strtotime($previous_inv_rec->transaction_date);

        $seq_expected_year = date('Y', $trx_date_unix);
        $next_seq_expected = (int) $this->account_statement_model->get_next_seq($this->franchise->franchise_id, $seq_expected_year, 2);

        foreach ($data as $key => $value)
        {
            $transaction_date_unix = strtotime($value->transaction_date);
            if($previous_inv_date_unix > $transaction_date_unix)
            {
                $this->_response_json([
                    'status' => 0,
                    'message' => 'Gagal commit invoice, tanggal tidak urut'
                ]);
            }
            else
            {
                $previous_inv_date_unix = $value->transaction_date;
            }


            if(date('Y', $transaction_date_unix) == $seq_expected_year)
            {
                if($next_seq_expected == $value->seq_invoice)
                {
                    $next_seq_expected = $value->seq_invoice + 1;
                }
                else
                {
                    $this->_response_json([
                        'status' => 0,
                        'message' => 'Gagal commit invoice, nomor invoice tidak urut, silahkan klik sort'
                    ]);
                }
            }
            else
            {
                $seq_expected_year = date('Y', $transaction_date_unix);
                $next_seq_expected = (int) $this->account_statement_model->get_next_seq($this->franchise->franchise_id, $seq_expected_year, 2);

                if($next_seq_expected == $value->seq_invoice)
                {
                    $next_seq_expected = $value->seq_invoice + 1;
                }
                else
                {
                    $this->_response_json([
                        'status' => 0,
                        'message' => 'Gagal commit invoice, nomor invoice tidak urut, silahkan klik sort'
                    ]);
                }
            }
        }
    }
}
