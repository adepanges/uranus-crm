<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Badge_model extends Penjualan_Model {
    protected
        $profile = [],
        $role = [],
        $tim = [],
        $join_sale = '',
        $where_sale = '',
        $join = '',
        $where = '';

    function set_viewer_profile($profile)
    {
        $this->profile = $profile;
    }

    function set_viewer_tim($tim)
    {
        $this->tim = $tim;
    }

    function set_viewer_role($role)
    {
        $this->role = $role;
        switch ($role['role_id']) {
            case 5:
                    $this->join = "LEFT JOIN orders_process fu ON a.order_id = fu.order_id AND fu.order_status_id = 2";
                    $this->where = " AND fu.user_id = {$this->profile['user_id']}";
                    $this->join_sale = $this->join;
                    $this->where_sale = $this->where;
                break;

            case 6:
                    $this->join = "LEFT JOIN orders_process fu ON a.order_id = fu.order_id AND fu.order_status_id = 2";
                    $this->where = "AND fu.user_id IN (SELECT user_id FROM management_team_cs_member WHERE team_cs_id = {$this->tim->team_cs_id})";

                    $this->join_sale = "LEFT JOIN orders_process fu ON a.order_id = fu.order_id AND fu.order_status_id = 6";
                    $this->where_sale = $this->where;
                break;
        }
    }

    function new()
    {
        return $this->db->query("SELECT count(*) as count
            FROM orders a
            WHERE
                a.order_status_id = 1 AND
                a.is_deleted = 0 AND
                (a.orders_double_id IS NULL OR a.orders_double_id = 0)")->first_row();
    }

    function assigned()
    {
        $where = '';
        if($this->role['role_id'] == 6)
        {
            $where = "AND fu.user_id IN (SELECT user_id FROM management_team_cs_member WHERE team_cs_id = {$this->tim->team_cs_id})";
        } else if($this->role['role_id'] == 5)
        {
            $where = "AND fu.user_id = {$this->profile['user_id']}";
        }

        return $this->db->query("SELECT count(*) as count
            FROM orders a
            LEFT JOIN orders_process fu ON a.order_id = fu.order_id AND fu.order_status_id = 10
            WHERE
                a.order_status_id = 10 AND
                a.is_deleted = 0 AND
                (a.orders_double_id IS NULL OR a.orders_double_id = 0) {$where}")->first_row();
    }

    function double()
    {
        return $this->db->query("SELECT COUNT(a.orders_double_id) AS `count`
            FROM orders_double a
            LEFT JOIN (
                SELECT orders_double_id, MAX(order_status_id) AS max_order_status_id
                FROM orders GROUP BY orders_double_id
                ) b ON a.orders_double_id = b.orders_double_id
            WHERE
                a.status = 1 AND
                (
                    b.max_order_status_id = 1 OR
                    {$this->profile['user_id']} in (
                        SELECT w.user_id
                        FROM orders z
                        LEFT JOIN orders_process w ON z.order_id = w.order_id
                        WHERE z.`version` = 1 AND
                            z.is_deleted = 0 AND
                            z.orders_double_id = a.orders_double_id AND
                            z.order_status_id > 1
                    )
                )")->first_row();
    }


    function pending()
    {
        return $this->db->query("SELECT count(*) as count
            FROM orders a
            {$this->join}
            WHERE
                a.order_status_id = 3 AND
                a.is_deleted = 0 AND
                (a.orders_double_id IS NULL OR a.orders_double_id = 0)
                $this->where")->first_row();
    }

    function confirm_buy()
    {
        return $this->db->query("SELECT count(*) as count
            FROM orders a
            {$this->join}
            WHERE
                a.order_status_id = 5 AND
                a.is_deleted = 0 AND
                (a.orders_double_id IS NULL OR a.orders_double_id = 0)
                $this->where")->first_row();
    }

    function verify()
    {
        return $this->db->query("SELECT count(*) as count
            FROM orders a
            {$this->join}
            WHERE
                a.order_status_id = 6 AND
                a.is_deleted = 0 AND
                (a.orders_double_id IS NULL OR a.orders_double_id = 0)
                $this->where")->first_row();
    }

    function sale()
    {
        $today = date('Y-m-d');
        return $this->db->query("SELECT count(*) as count
            FROM orders a
            LEFT JOIN orders_process b ON a.order_id = b.order_id AND b.order_status_id = 7
            {$this->join_sale}
            WHERE a.order_status_id > 6 AND
                a.is_deleted = 0 AND
                (a.orders_double_id IS NULL OR a.orders_double_id = 0) AND
                b.created_at BETWEEN '{$today} 00:00:00' AND '{$today} 23:59:59'
                $this->where_sale")->first_row();
    }

    function cancel()
    {
        return $this->db->query("SELECT count(*) as count
            FROM orders a
            {$this->join}
            WHERE
                a.order_status_id = 4 AND
                a.is_deleted = 0 AND
                (a.orders_double_id IS NULL OR a.orders_double_id = 0)
                $this->where")->first_row();
    }

    function trash()
    {
        return $this->db->query("SELECT count(*) as count
            FROM orders
            WHERE is_deleted = 1")->first_row();
    }
}
