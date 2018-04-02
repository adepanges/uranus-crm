<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_model extends Logistik_Model {

    function get_available_print($id = [])
    {
        $id_clean = [];
        foreach ($id as $key => $value) {
            if((int) $value != 0) $id_clean[] = (int) $value;
        }

        $this->db->where_in('order_id', $id_clean);
        $res = $this->db->get('orders_invoices');
        $this->set_printed($id_clean);
        return $res;
    }

    protected function set_printed($id)
    {
        $this->db->set('printed', 1);
        $this->db->where_in('order_id', $id);
        return $res = $this->db->update('orders_invoices');
    }
}
