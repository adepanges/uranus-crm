<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders_model extends SSO_Model {
    function get_follow_up_by_userid($id)
    {
        $sql = "SELECT a.order_id, b.created_at
            FROM orders a
            LEFT JOIN orders_process b ON a.order_id = b.order_id
            WHERE a.order_status_id = 2 AND b.user_id = ?
            ORDER BY b.created_at ASC
            LIMIT 1";
        return $this->db->query($sql, [$id]);
    }
}
