<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Statistik_model extends Portal_Model {

	public function cs($user_id, $start_date = '', $end_date = '')
	{
        $user_id = (int) $user_id;

        $start_date = !empty($start_date)?$start_date.' 00:00:00':date('Y-m-01 00:00:00');
        $end_date = !empty($end_date)?$end_date.' 23:59:59':date('Y-m-d 23:59:59');

        $sql = "SELECT
                CONCAT(a.day,'/',a.month) AS periode,
                (
                    SELECT COUNT(DISTINCT z.order_id) AS total
                    FROM orders_process z
                    WHERE
                        order_status_id = 2 AND
                        user_id = {$user_id} AND
                        DATE(created_at) = a.db_date
                ) AS total_fu,
                (
                    SELECT COUNT(DISTINCT z.order_id) AS total
                    FROM orders_process z
                    WHERE
                        z.order_status_id = 3 AND
                        z.user_id = {$user_id} AND
                        DATE(z.created_at) = a.db_date
                ) AS total_pending,
                (
                    SELECT COUNT(DISTINCT z.order_id) AS total
                    FROM orders_process z
                    WHERE
                        z.order_status_id = 4 AND
                        z.user_id = {$user_id} AND
                        DATE(z.created_at) = a.db_date
                ) AS total_cancel,
                (
                    SELECT COUNT(DISTINCT z.order_id) AS total
                    FROM orders_process z
                    WHERE
                        z.order_status_id = 5 AND
                        z.user_id = {$user_id} AND
                        DATE(z.created_at) = a.db_date
                ) AS total_confirm,
                (
                    SELECT COUNT(DISTINCT z.order_id) AS total
                    FROM orders_process z
                    WHERE
                        z.order_status_id = 6 AND
                        z.user_id = {$user_id} AND
                        DATE(z.created_at) = a.db_date
                ) AS total_verify,
                (
                    SELECT COUNT(DISTINCT z.order_id) AS total
                    FROM orders_process z
                    WHERE
                        z.order_status_id = 6 AND
                        z.user_id = {$user_id} AND
                        (
                            SELECT process_id
                            FROM orders_process
                            WHERE
                                order_id = z.order_id AND
                                order_status_id = 7 AND
                                DATE(created_at) = a.db_date
                            LIMIT 1
                        ) IS NOT NULL
                ) AS total_sale
            FROM time_dimension a
            WHERE a.db_date BETWEEN ? AND ?";

        $res = $this->db->query($sql, [$start_date, $end_date]);

        // echo $this->db->last_query();
        // exit;

        return $res;
	}

    public function all($start_date = '', $end_date = '')
	{
        $start_date = !empty($start_date)?$start_date.' 00:00:00':date('Y-m-01 00:00:00');
        $end_date = !empty($end_date)?$end_date.' 23:59:59':date('Y-m-d 23:59:59');

        $sql = "SELECT
                CONCAT(a.day,'/',a.month) AS periode,
                (
                    SELECT COUNT(DISTINCT z.order_id) AS total
                    FROM orders_process z
                    WHERE
                        order_status_id = 2 AND
                        DATE(created_at) = a.db_date
                ) AS total_fu,
                (
                    SELECT COUNT(DISTINCT z.order_id) AS total
                    FROM orders_process z
                    WHERE
                        z.order_status_id = 3 AND
                        DATE(z.created_at) = a.db_date
                ) AS total_pending,
                (
                    SELECT COUNT(DISTINCT z.order_id) AS total
                    FROM orders_process z
                    WHERE
                        z.order_status_id = 4 AND
                        DATE(z.created_at) = a.db_date
                ) AS total_cancel,
                (
                    SELECT COUNT(DISTINCT z.order_id) AS total
                    FROM orders_process z
                    WHERE
                        z.order_status_id = 5 AND
                        DATE(z.created_at) = a.db_date
                ) AS total_confirm,
                (
                    SELECT COUNT(DISTINCT z.order_id) AS total
                    FROM orders_process z
                    WHERE
                        z.order_status_id = 6 AND
                        DATE(z.created_at) = a.db_date
                ) AS total_verify,
                (
                    SELECT COUNT(DISTINCT z.order_id) AS total
                    FROM orders_process z
                    WHERE
                        z.order_status_id = 7 AND
                        DATE(z.created_at) = a.db_date
                ) AS total_sale

            FROM time_dimension a
            WHERE a.db_date BETWEEN ? AND ?";

        return $this->db->query($sql, [$start_date, $end_date]);
	}
}
