<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank_statement_model extends Keuangan_Model {

    protected
        $datatable_param = NULL,
        $table = 'account_statement',
        $orderable_field = [],
        $fillable_field = [],
        $searchable_field = [];

    function get_bca($params)
    {
        $params['franchise_id'] = (int) $params['franchise_id'];

        $sql = "SELECT a.*
            FROM account_statement a
            WHERE
                franchise_id = {$params['franchise_id']} AND
                payment_method_id = 2 AND
                a.transaction_date BETWEEN '{$params['date_start']}' AND '{$params['date_end']}'
            ORDER BY a.transaction_date, a.account_statement_seq ASC";

        return $this->db->query($sql)->result();
    }

    function get_bri($params)
    {
        $params['franchise_id'] = (int) $params['franchise_id'];

        $sql = "SELECT a.*
            FROM account_statement a
            WHERE
                franchise_id = {$params['franchise_id']} AND
                payment_method_id = 3 AND
                a.transaction_date BETWEEN '{$params['date_start']}' AND '{$params['date_end']}'
            ORDER BY a.transaction_date, a.account_statement_seq ASC";

        return $this->db->query($sql)->result();
    }

    function get_mandiri($params)
    {
        $params['franchise_id'] = (int) $params['franchise_id'];

        $sql = "SELECT a.*
            FROM account_statement a
            WHERE
                franchise_id = {$params['franchise_id']} AND
                payment_method_id = 4 AND
                a.transaction_date BETWEEN '{$params['date_start']}' AND '{$params['date_end']}'
            ORDER BY a.transaction_date, a.account_statement_seq ASC";

        return $this->db->query($sql)->result();
    }

    function get($account_statement_id)
    {
        return $this->db->limit(1)->get_where("account_statement", [
            'account_statement_id' => (int) $account_statement_id
        ])->first_row();
    }

    function get_balance_before($franchise_id = 0, $payment_method_id = 0, $account_statement_seq)
    {
        $res = $this->db->query("SELECT a.*
            FROM account_statement a
            WHERE
                franchise_id = {$franchise_id} AND
                payment_method_id = {$payment_method_id} AND
                account_statement_seq < {$account_statement_seq}
            ORDER BY a.account_statement_seq DESC LIMIT 1")->first_row();

        return (!empty($res) && isset($res->balance))?$res->balance:0;
    }

    function get_sequence_smallest($franchise_id = 0, $payment_method_id = 0, $smallest_sequence)
    {
        $res = $this->db->query("SELECT a.*
            FROM account_statement a
            WHERE
                franchise_id = {$franchise_id} AND
                payment_method_id = {$payment_method_id} AND
                account_statement_seq < {$smallest_sequence}
            ORDER BY a.account_statement_seq DESC LIMIT 1")->first_row();

        return (!empty($res) && isset($res->account_statement_seq))?$res->account_statement_seq:0;
    }

    function upd_sequence($account_statement_id, $current_account_statement_seq, $change_account_statement_seq)
    {
        $this->db->where('account_statement_id', $account_statement_id);
        $this->db->where('account_statement_seq', $current_account_statement_seq);
        return $this->db->update('account_statement', [
            'account_statement_seq' => $change_account_statement_seq
        ]);
    }

    function upd_sequence_on_it_inc($payment_method_id, $account_statement_id, $target_account_statement_seq)
    {
        $this->db->where('payment_method_id', $payment_method_id);
        $this->db->where('account_statement_id !=', $account_statement_id);
        $this->db->where('account_statement_seq', $target_account_statement_seq);
        return $this->db->update('account_statement', [
            'account_statement_seq' => $target_account_statement_seq + 1
        ]);
    }

    function upd_sequence_on_it_dec($payment_method_id, $account_statement_id, $target_account_statement_seq)
    {
        $this->db->where('payment_method_id', $payment_method_id);
        $this->db->where('account_statement_id !=', $account_statement_id);
        $this->db->where('account_statement_seq', $target_account_statement_seq);
        return $this->db->update('account_statement', [
            'account_statement_seq' => $target_account_statement_seq - 1
        ]);
    }

    function upd_balance($account_statement_id, $account_statement_seq, $balance)
    {
        $this->db->where('account_statement_id', $account_statement_id);
        $this->db->where('account_statement_seq', $account_statement_seq);
        return $this->db->update('account_statement', [
            'balance' => $balance
        ]);
    }
}
