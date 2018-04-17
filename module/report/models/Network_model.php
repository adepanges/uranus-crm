<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Network_model extends Report_Model {
    protected
        $datatable_param = NULL,
        $table = '',
        $orderable_field = ['network_id','name','total_leads','total_conversions','total_sales'],
        $searchable_field = ['name','total_leads','total_conversions','total_sales'];

    function get_datatable($params = [])
    {
        $params_sql = [
            'date_start' => date('Y-m-01 00:00:00'),
            'date_end' => date('Y-m-d H:i:s')
        ];

        if(
            isset($params['date_start']) && !empty($params['date_start'])
        )
        {
            $params_sql['date_start'] = $this->db->escape($params['date_start'].' 00:00:00');
        }

        if(
            isset($params['date_end']) && !empty($params['date_end'])
        )
        {
            $params_sql['date_end'] = $this->db->escape($params['date_end'].' 23:59:59');
        }

        $sql = "AND z.created_at BETWEEN ". $params_sql['date_start'] ." AND ". $params_sql['date_end'];

        $sql = "SELECT
                a.network_id, a.name,
                (
                    SELECT COUNT(*)
                    FROM orders_network_postback z
                    WHERE z.network_id = a.network_id AND
                    z.event_id = 3 AND
                    (z.postback_response LIKE '%success%' OR z.postback_response LIKE '%duplicate%')
                    $sql
                ) AS total_leads,
                (
                    SELECT COUNT(*)
                    FROM orders_network_postback z
                    WHERE z.network_id = a.network_id AND
                    z.event_id = 4 AND
                    (z.postback_response LIKE '%success%' OR z.postback_response LIKE '%duplicate%')
                    $sql
                ) AS total_conversions,
                (
                    SELECT COUNT(*)
                    FROM orders_network_postback z
                    WHERE z.network_id = a.network_id AND
                    z.event_id = 5 AND
                    (z.postback_response LIKE '%success%' OR z.postback_response LIKE '%duplicate%')
                    $sql
                ) AS total_sales
            FROM network a
            WHERE a.status = 1";

        $sql = $this->_combine_datatable_param($sql);
        $sql_count = $this->_combine_datatable_param($sql, TRUE);
        return [
            'row' => $this->db->query($sql)->result(),
            'total' => $this->db->query($sql_count)->row()->count
        ];
    }
}
