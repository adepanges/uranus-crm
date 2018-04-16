<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Simple_model extends Report_Model {
    protected
        $datatable_param = NULL,
        $table = 'product',
        $orderable_field = ['name','total_penjualan','total_follow_up','total_pending','total_cancel','total_confirm_buy','total_sale'],
        $searchable_field = ['name'];

    function get_datatable()
    {
        $sql = "SELECT
            	a.user_id, CONCAT(a.first_name,' ',a.last_name) AS name, b.user_role_id,
            	(SELECT COUNT(*) AS total FROM orders_process WHERE user_id = a.user_id AND order_status_id = 2) AS total_follow_up,
            	(
                    SELECT COUNT(*) AS total FROM orders z
                    LEFT JOIN orders_process p ON z.order_id = p.order_id AND p.order_status_id = 2
                    WHERE z.order_status_id = 3 AND p.user_id = a.user_id
                ) AS total_pending,
                (
                    SELECT COUNT(*) AS total FROM orders z
                    LEFT JOIN orders_process p ON z.order_id = p.order_id AND p.order_status_id = 2
                    WHERE z.order_status_id = 4 AND p.user_id = a.user_id
                ) AS total_cancel,
                (
                    SELECT COUNT(*) AS total FROM orders z
                    LEFT JOIN orders_process p ON z.order_id = p.order_id AND p.order_status_id = 2
                    WHERE z.order_status_id = 5 AND p.user_id = a.user_id
                ) AS total_confirm_buy,
                (
                    SELECT COUNT(*) AS total FROM orders z
                    LEFT JOIN orders_process p ON z.order_id = p.order_id AND p.order_status_id = 2
                    WHERE z.order_status_id = 6 AND p.user_id = a.user_id
                ) AS total_verify,
            	(
            		SELECT COUNT(*) AS total FROM orders_process z
            		WHERE z.user_id = a.user_id AND z.order_status_id = 6 AND
            		(SELECT w.process_id FROM orders_process w WHERE w.order_id = z.order_id AND w.order_status_id > 6 LIMIT 1) IS NOT NULL
            	) AS total_sale,
            	(
            		SELECT SUM(p.total_price) AS total FROM orders_process z
            		LEFT JOIN orders p ON p.order_id = z.order_id
            		WHERE z.user_id = a.user_id AND z.order_status_id = 6 AND
            		(SELECT w.process_id FROM orders_process w WHERE w.order_id = z.order_id AND w.order_status_id > 6 LIMIT 1) IS NOT NULL
            	) AS total_penjualan
            FROM sso_user a
            LEFT JOIN sso_user_role b ON a.user_id = b.user_id AND b.role_id = 5
            WHERE a.status = 1 AND b.user_role_id IS NOT NULL";

        $sql = $this->_combine_datatable_param($sql);
        $sql_count = $this->_combine_datatable_param($sql, TRUE);
        return [
            'row' => $this->db->query($sql)->result(),
            'total' => $this->db->query($sql_count)->row()->count
        ];
    }
}
