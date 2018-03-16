<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Network_postback_model extends Management_Model {
    protected
        $datatable_param = NULL,
        $table = 'network_postback',
        $orderable_field = ['name','status'],
        $fillable_field = ['network_id','event_id','link','status'],
        $searchable_field = ['link','event_name','trigger'];

    function get_datatable($network_id = 0)
    {
        $network_id = (int) $network_id;
        $sql = "SELECT a.*, c.trigger, b.name AS event_name
            FROM network_postback a
            LEFT JOIN master_event b ON a.event_id  = b.event_id
            LEFT JOIN (
            	SELECT event_id, GROUP_CONCAT(name SEPARATOR ', ') AS `trigger`
            	FROM master_order_status GROUP BY event_id
            	ORDER BY event_id ASC
            ) c ON a.event_id = c.event_id
            WHERE a.network_id = {$network_id}
            ORDER BY b.sort ASC";

        $sql = $this->_combine_datatable_param($sql);
        $sql_count = $this->_combine_datatable_param($sql, TRUE);
        return [
            'row' => $this->db->query($sql)->result(),
            'total' => $this->db->query($sql_count)->row()->count
        ];
    }

    function get_byid($id)
    {
        return $this->db->where('network_postback_id', $id)->limit(1)->get($this->table)->first_row();
    }

    function del($id)
    {
        return $this->db->delete($this->table, ['network_postback_id' => ((int) $id)]);
    }

    function upd($data, $id)
    {
        $this->db->where('network_postback_id', $id);
        return $this->db->update($this->table, $this->_sanity_field($data));
    }

    function add($data)
    {
        return $this->db->insert($this->table, $this->_sanity_field($data));
    }
}
