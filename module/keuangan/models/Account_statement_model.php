<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account_statement_model extends Keuangan_Model {

    protected
        $datatable_param = NULL,
        $table = 'account_statement',
        $orderable_field = [],
        $fillable_field = ['franchise_id','payment_method_id','seq_invoice','generated_invoice','commit','transaction_type','transaction_date','transaction_amount','note','is_use','user_id','updated_at','created_at','fix'],
        $searchable_field = ['account_name','generated_invoice','transaction_amount','note'];

    function get_datatable($params)
    {
        $params['franchise_id'] = (int) $params['franchise_id'];

        $sql = "SELECT
                a.*, b.name as account_name
            FROM account_statement a
            LEFT JOIN master_payment_method b ON a.payment_method_id = b.payment_method_id
            WHERE
                a.franchise_id = {$params['franchise_id']} AND
                a.transaction_date BETWEEN '{$params['date_start']}' AND '{$params['date_end']}'
            ORDER BY a.transaction_date ASC, a.seq_invoice ASC";

        $sql_row = $this->_combine_datatable_param($sql);
        $sql_count = $this->_combine_datatable_param($sql, TRUE);
        return [
            'row' => $this->db->query($sql_row)->result(),
            'total' => $this->db->query($sql_count)->row()->count
        ];
    }

    function get_byid($id)
    {
        return $this->db->where('account_statement_id', $id)->limit(1)->get($this->table)->first_row();
    }

    function get_uncommit($franchise_id)
    {
        $sql = "SELECT * FROM account_statement
            WHERE franchise_id = ? AND commit != 1
            ORDER BY transaction_date ASC, seq_invoice ASC";
        return $this->db->query($sql, [(int) $franchise_id]);
    }

    function del($id)
    {
        return $this->db->delete($this->table, ['account_statement_id' => ((int) $id)]);
    }

    function upd($data, $id)
    {
        $this->db->where('account_statement_id', $id);
        return $this->db->update($this->table, $this->_sanity_field($data));
    }

    function add($data)
    {
        return $this->db->insert($this->table, $this->_sanity_field($data));
    }

    function get_next_seq($franchise_id, $year, $commited = 0)
    {
        $where = '';
        if($commited == 1)
        {
            $where = "AND (commit = 1 OR fix = 1)";
        } else if($commited == 2)
        {
            $where = "AND commit = 1";
        }

        $last_sequence = 0;
        $res = $this->db->query('SELECT
                MAX(seq_invoice) AS last_sequence
            FROM account_statement
            WHERE franchise_id = ? AND YEAR(transaction_date) = ? '.$where, [
                $franchise_id, $year
            ]);

        if($res->num_rows() > 0)
        {
            $last_sequence = (int) $res->first_row()->last_sequence;
        }

        return $last_sequence + 1;
    }

    function commit_invoice_number($franchise_id)
    {
        $this->db->where('franchise_id', (int) $franchise_id);
        $this->db->where('fix', 1);
        $this->db->where('commit !=', 1);
        return $this->db->update($this->table, ['commit' => 1]);
    }

    function set_uncommit_to_unfix($franchise_id)
    {
        $this->db->where('franchise_id', $franchise_id);
        $this->db->where('commit !=', 1);
        return $this->db->update($this->table, ['fix' => 0]);
    }

    function get_last_date_inv($franchise_id)
    {
        $sql = "SELECT * FROM account_statement
            WHERE franchise_id = ? AND commit = 1
            ORDER BY transaction_date DESC LIMIT 1";
        return $this->db->query($sql, [(int) $franchise_id]);
    }
}
