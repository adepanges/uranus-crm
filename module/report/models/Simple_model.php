<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Simple_model extends Report_Model {
    protected
        $datatable_param = NULL,
        $table = 'product',
        $orderable_field = ['name','total_penjualan','total_follow_up','total_pending','total_cancel','total_confirm_buy','total_sale','total_product'],
        $searchable_field = ['name'];

    function get_datatable($params)
    {
        $params['date_start'] = $this->db->escape($params['date_start']);
        $params['date_end'] = $this->db->escape($params['date_end']);

        $join = "";
        $join_other = "";
        $join_where = "";
        $where = "";
        $where_sale = "";
        $join_where_sale = '';

        switch ($params['by_date']) {
            case 'orders':
                $join_other = "LEFT JOIN orders p ON p.order_id = z.order_id";
                $where = "(p.created_at BETWEEN {$params['date_start']} AND {$params['date_end']}) AND";
                $join_where_sale = $where;
                break;

            case 'action':
                // $join = "LEFT JOIN orders_process fu ON fu.order_id = z.order_id and fu.order_status_id = 2";
                // $join_where = "(fu.created_at BETWEEN {$params['date_start']} AND {$params['date_end']}) AND";
                // $join_where_sale = $join_where;
                $join_other = "";
                $where = "(z.created_at BETWEEN {$params['date_start']} AND {$params['date_end']}) AND";
                $where_sale = "(w.created_at BETWEEN {$params['date_start']} AND {$params['date_end']}) AND";
                break;
        }

        $global_where = '';
        if($params['role_id'] == 6)
        {
            $global_where = 'AND a.user_id IN (SELECT user_id FROM management_team_cs_member
            WHERE team_cs_id IN (SELECT team_cs_id FROM management_team_cs WHERE leader_id = 57 AND `status` = 1) AND `status` = 1)';
        }

        $sql = "SELECT
            	a.user_id, CONCAT(a.first_name,' ',a.last_name) AS name, b.user_role_id,
            	(
                    SELECT COUNT(DISTINCT z.order_id) AS total FROM orders_process z
                    $join_other
                    WHERE $where
                    z.user_id = a.user_id AND z.order_status_id = 2
                ) AS total_follow_up,
            	(
                    SELECT COUNT(DISTINCT p.order_id) AS total FROM orders p
                    LEFT JOIN orders_process z ON p.order_id = z.order_id AND z.order_status_id = 3
                    $join
                    WHERE $join_where $where p.order_status_id = 3 AND z.user_id = a.user_id
                ) AS total_pending,
                (
                    SELECT COUNT(DISTINCT p.order_id) AS total FROM orders p
                    LEFT JOIN orders_process z ON p.order_id = z.order_id AND z.order_status_id = 4
                    $join
                    WHERE $join_where $where p.order_status_id = 4 AND z.user_id = a.user_id
                ) AS total_cancel,
                (
                    SELECT COUNT(DISTINCT p.order_id) AS total FROM orders p
                    LEFT JOIN orders_process z ON p.order_id = z.order_id AND z.order_status_id = 5
                    $join
                    WHERE $join_where $where p.order_status_id = 5 AND z.user_id = a.user_id
                ) AS total_confirm_buy,
                (
                    SELECT COUNT(DISTINCT p.order_id) AS total FROM orders p
                    LEFT JOIN orders_process z ON p.order_id = z.order_id AND z.order_status_id = 6
                    $join
                    WHERE $join_where $where p.order_status_id = 6 AND z.user_id = a.user_id
                ) AS total_verify,
                (
                    SELECT COUNT(DISTINCT z.order_id) AS total FROM orders_process z
                    $join
                    $join_other
                    WHERE z.user_id = a.user_id AND z.order_status_id = 6 AND
                    $join_where_sale
                    (SELECT w.process_id FROM orders_process w WHERE $where_sale w.order_id = z.order_id AND w.order_status_id = 7 LIMIT 1) IS NOT NULL
                ) AS total_sale,
                (
                    SELECT SUM(p.total_price) AS total FROM orders_process z
                    LEFT JOIN orders p ON p.order_id = z.order_id
                    $join
                    WHERE z.user_id = a.user_id AND z.order_status_id = 6 AND p.is_deleted = 0 AND
                    $join_where_sale
                    (SELECT w.process_id FROM orders_process w WHERE $where_sale w.order_id = z.order_id AND w.order_status_id = 7 LIMIT 1) IS NOT NULL
                ) AS total_penjualan,
                (
                    SELECT SUM(cart.total) AS total FROM orders_process z
                   LEFT JOIN orders p ON p.order_id = z.order_id
                   LEFT JOIN (
                       SELECT order_id, SUM(qty) AS total
                       FROM orders_cart
                       WHERE product_id IS NOT NULL AND product_id != 0 GROUP BY order_id
                   ) cart ON cart.order_id = p.order_id
                   $join
                   WHERE z.user_id = a.user_id AND z.order_status_id = 6 AND p.is_deleted = 0 AND
                   $join_where_sale
                   (SELECT w.process_id FROM orders_process w WHERE $where_sale w.order_id = z.order_id AND w.order_status_id = 7 LIMIT 1) IS NOT NULL
               ) AS total_product
            FROM sso_user a
            LEFT JOIN sso_user_role b ON a.user_id = b.user_id AND b.role_id = 5
            WHERE a.status = 1 AND b.user_role_id IS NOT NULL $global_where";

        // echo $sql;
        // exit;

        $sql_report = $this->_combine_datatable_param($sql);
        $sql_count = $this->_combine_datatable_param($sql, TRUE);
        return [
            'row' => $this->db->query($sql_report)->result(),
            'total' => $this->db->query($sql_count)->row()->count
        ];
    }

    function get_information($params)
    {
        $params['date_start'] = $this->db->escape($params['date_start']);
        $params['date_end'] = $this->db->escape($params['date_end']);

        $join = "";
        $where = "";

        switch ($params['by_date']) {
            case 'orders':
                $where = "(p.created_at BETWEEN {$params['date_start']} AND {$params['date_end']}) AND";
                break;

            case 'action':
                // LEFT JOIN orders_process fu ON p.order_id = fu.order_id AND fu.order_status_id = 2LEFT JOIN orders_process fu ON p.order_id = fu.order_id AND fu.order_status_id = 2
                // (fu.created_at BETWEEN {$params['date_start']} AND {$params['date_end']}) AND
                $join = "LEFT JOIN orders_process z ON p.order_id = z.order_id AND z.order_status_id = 7";
                $where = "(z.created_at BETWEEN {$params['date_start']} AND {$params['date_end']}) AND";
                break;
        }

        $global_where = '';
        if($params['role_id'] == 6)
        {
            $where .= " fu.user_id IN (SELECT user_id FROM management_team_cs_member
            WHERE team_cs_id IN (SELECT team_cs_id FROM management_team_cs WHERE leader_id = 57 AND `status` = 1) AND `status` = 1) AND";
        }

        $sql = "SELECT COUNT(DISTINCT p.order_id) AS total_sale, SUM(p.total_price) AS total_price, SUM(cart.qty_total) AS product_total
            FROM orders p
            LEFT JOIN (
                       SELECT order_id, SUM(qty) AS qty_total
                       FROM orders_cart
                       WHERE product_id IS NOT NULL AND product_id != 0 GROUP BY order_id
            ) cart ON cart.order_id = p.order_id
            LEFT JOIN orders_process fu ON p.order_id = fu.order_id and fu.order_status_id = 2
            $join
            WHERE $where p.order_status_id >= 7";

        // echo $sql;
        // exit;

        return $this->db->query($sql)->first_row();
    }
}
