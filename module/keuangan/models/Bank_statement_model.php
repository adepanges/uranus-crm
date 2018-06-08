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
            a.transaction_date BETWEEN '{$params['date_start']}' AND '{$params['date_end']}'";

        return [
            'row' => $this->db->query($sql)->result()
        ];
    }
}
