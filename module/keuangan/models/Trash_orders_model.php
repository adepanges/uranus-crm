<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trash_orders_model extends Keuangan_Model {
    protected
        $datatable_param = NULL,
        $table = 'orders',
        $orderable_field = ['name','franchise_name','franchise_name','jumlah_cs','status'],
        $fillable_field = ['payment_method_id'],
        $searchable_field = ['payment_method_id','logistic_id','order_status_id','logistics_status_id','call_method_id','order_status','logistics_status','shipping_code','call_method','order_code','customer_info','customer_address'];

    function get_datatable($params = [])
    {
        $sql = "SELECT
            a.*, b.icon AS call_method_icon,
            (SELECT package_name FROM orders_cart WHERE order_id = a.order_id AND is_package = 1 LIMIT 1) AS package_name
        FROM {$this->table} a
        LEFT JOIN master_call_method b ON a.call_method_id = b.call_method_id
        WHERE a.is_deleted = 1";

        $sql_select = $this->_combine_datatable_param($sql);
        $sql_count = $this->_combine_datatable_param($sql, TRUE);

        return [
            'row' => $this->db->query($sql_select)->result(),
            'total' => $this->db->query($sql_count)->row()->count
        ];
    }

    function pulihkan($orders_id)
    {
        $this->db->where('order_id', $orders_id);
        return $this->db->update($this->table, [
            'orders_double_id' => NULL,
            'is_deleted' => 0
        ]);
    }

    function del($id = 0)
    {
        if(!is_array($id))
        {
            $id = [$id];
        }
        $this->db->where_in('order_id', $id);
        return $this->db->delete('orders');
    }
}
