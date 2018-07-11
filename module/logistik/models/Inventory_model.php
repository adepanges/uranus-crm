<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_model extends Logistik_Model {
    protected
        $datatable_param = NULL,
        $table = 'inventory',
        $orderable_field = [],
        $fillable_field = [],
        $searchable_field = [];

    function get_product_stock($franchise_id = 1)
    {
        return $this->db->query('SELECT a.*, (b.amount - b.used) AS current_stock
            FROM product a
            LEFT JOIN (
            	SELECT
            		franchise_id, product_id, SUM(amount) AS amount, SUM(used) AS used
            	FROM inventory WHERE `status` = 1 AND franchise_id = ?
            	GROUP BY franchise_id, product_id
            ) b ON b.product_id = a.product_id
            WHERE a.status = 1', [
                (int) $franchise_id
            ]);
    }

    function get_product($franchise_id = 1, $product_id = 1)
    {
        return $this->db->query('SELECT
                a.*, (b.amount - b.used) AS current_stock,
                b.amount AS jumlah_masuk,
                b.used AS jumlah_keluar
            FROM product a
            LEFT JOIN (
            	SELECT
            		franchise_id, product_id, SUM(amount) AS amount, SUM(used) AS used
            	FROM inventory WHERE `status` = 1 AND franchise_id = ?
            	GROUP BY franchise_id, product_id
            ) b ON b.product_id = a.product_id
            WHERE a.status = 1 AND a.product_id = ? LIMIT 1', [
                (int) $franchise_id,
                (int) $product_id
            ])->first_row();
    }

    function get_datatable($params = [])
    {
        $select = [];
        $join = [];
        $where = [];

        if(empty($where)) $where = ''; else $where = " AND ".implode(" AND ",$where);

        $sql = "SELECT a.*, b.username
            FROM inventory a
            LEFT JOIN sso_user b ON a.created_by_user_id = b.user_id
            $where
            ORDER BY a.arrived_at DESC, a.inventory_id ASC";

        $sql = $this->_combine_datatable_param($sql);
        $sql_count = $this->_combine_datatable_param("SELECT a.*
            FROM inventory a
            $where", TRUE);

        return [
            'row' => $this->db->query($sql)->result(),
            'total' => $this->db->query($sql_count)->row()->count
        ];
    }
}
