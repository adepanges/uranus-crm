<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_model extends Logistik_Model {

    function get_available_print($id = [])
    {
        $id_clean_array = [];
        foreach ($id as $key => $value) {
            if((int) $value != 0) $id_clean_array[] = (int) $value;
        }

        $id_clean = implode(',', $id_clean_array);

        $sql = "SELECT
                a.*, b.*
            FROM orders_invoices a
            LEFT JOIN franchise b ON a.franchise_id = b.franchise_id
            WHERE a.order_id IN ({$id_clean})";
        $res = $this->db->query($sql);
        $this->set_printed($id_clean_array);
        return $res;
    }

    protected function set_printed($id)
    {
        $this->db->set('printed', 1);
        $this->db->where_in('order_id', $id);
        return $res = $this->db->update('orders_invoices');
    }
}
