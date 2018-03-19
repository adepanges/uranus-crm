<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Network_model extends API_Model {

    function get_byid($id)
    {
        return $this->db->get_where('network', ['network_id' => $id]);
    }

    function orders_add($data, $catch, $cacth_field)
    {
        $catched = [];
        foreach ($cacth_field as $key => $value) {
            $catched[$value] = isset($catch[$value])?$catch[$value]:'';
        }
        $data['catch'] = json_encode($catched);
        return $this->db->insert('orders_network', $data);
    }
}
