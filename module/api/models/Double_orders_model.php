<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Double_orders_model extends API_Model {

    function init_double_orders($params, $customer_id)
    {
        $params['created_at'] = date('Y-m-d H:i:s');
        $params['status'] = 1;

        $this->db->where('customer_id', $customer_id);
        $this->db->update('orders_double', $this->_sanity_field($params, ['customer_name','customer_telephone','double_reason','created_at','status']));

        if($this->db->affected_rows())
        {
            return $this->_get_by_customer_id($customer_id)->first_row();
        }
        else
        {
            $params['customer_id'] = $customer_id;
            $this->_add($params);
            return $this->_get_by_orders_double_id($this->db->insert_id())->first_row();
        }
    }

    function get_existing_orders_not_yet_sale_from_customer($customer_id)
    {
        $this->db->select('order_id');
        return $this->db->get_where('orders', [
            'order_status_id <' => 7,
            'order_status_id !=' => 4,
            'customer_id' => $customer_id
        ]);
    }

    function set_orders_double($orders_double_id = 0, $order_id = [])
    {
        $this->db->where_in('order_id', $order_id);
        return $this->db->update('orders', [
            'orders_double_id' => $orders_double_id,
        ]);
    }

    protected function _get_by_customer_id($customer_id)
    {
        return $this->db->limit(1)->get_where('orders_double', ['customer_id' => $customer_id]);
    }

    protected function _get_by_orders_double_id($orders_double_id)
    {
        return $this->db->limit(1)->get_where('orders_double', ['orders_double_id' => $orders_double_id]);
    }

    protected function _add($params)
    {
        return $this->db->insert('orders_double', $this->_sanity_field($params, ['customer_id','customer_name','customer_telephone','double_reason','created_at','status',]));
    }
}
