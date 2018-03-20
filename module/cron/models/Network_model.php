<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Network_model extends Cron_Model {

    function get_queue()
    {
        return $this->db->query("SELECT
        	a.network_id, c.process_id, c.order_status_id, a.name, a.catch AS catch_field,
        	b.order_id, b.catch, c.event_postback_status, d.name AS orders_trigger, e.name AS event_name,
            e.event_id, f.link, f.network_postback_id, b.order_network_id
        FROM network a
        LEFT JOIN orders_network b ON a.network_id = b.network_id
        LEFT JOIN orders_process c ON b.order_id = c.order_id
        LEFT JOIN master_order_status d ON c.order_status_id = d.order_status_id
        LEFT JOIN master_event e ON d.event_id = e.event_id
        LEFT JOIN network_postback f ON a.network_id = f.network_id AND e.event_id = f.event_id
        WHERE a.status = 1 AND c.event_postback_status = 0 AND e.name != 'NO_EVENT' AND f.status = 1
        ORDER BY c.process_id ASC
        LIMIT 10");
    }

    function add_network_postback($params = [])
    {
        $params['status'] = 1;
        $this->db->insert('orders_network_postback', $this->_sanity_field($params, ['order_id','network_id','network_postback_id','order_network_id','process_id','event_id','orders_trigger','event_name','catch_data','url','postback_response','network_name','status']));
    }

    function upd_process_postback($process_id)
    {
        $this->db->where_in('process_id', $process_id);
        return $this->db->update('orders_process', ['event_postback_status' => 1]);
    }
}
