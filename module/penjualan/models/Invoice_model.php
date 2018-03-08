<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_model extends Penjualan_Model {

    function publish_v1($order_id = 0, $time = '')
    {
        if(empty($time))
        {
            $time = date('Y-m-d H:i:s');
        }

        $orders = $this->get_orders_info($order_id);
        $customer = $this->get_customer_info($orders->customer_id);
        $customer_address = $this->get_customer_address($orders->customer_id, $orders->customer_address_id);
        $orders_cart = $this->get_cart($orders->order_id);
        $invoice_number = $this->create_invoice_number_v1($time);

        $invoice_data = [
            'order_id' => $orders->order_id,
            'customer_id' => $orders->customer_id,
            'customer_address_id' => $orders->customer_address_id,
            'invoice_number' => $invoice_number,
            'order_code' => $orders->order_code,
            'customer' => json_encode($customer),
            'customer_address' => json_encode($customer_address),
            'order_cart' => json_encode($orders_cart),
            'total_price' => $orders->total_price,
            'billed_date' => $time,
            'paid_date' => $time,
            'version' => 1
        ];

        return $this->db->insert('orders_invoices', $invoice_data);
    }

    protected function create_invoice_number_v1($time_now)
    {
        $time = strtotime($time_now);
        $kode = 'DKI';
        $date_inv = date('Ymd', $time);
        $inv_number = 1;

        $last_inv = $this->db->query('SELECT invoice_number
            FROM orders_invoices
            WHERE YEAR(paid_date) = ?
            ORDER BY order_invoice_id DESC
            LIMIT 1', [date('Y', $time)])->first_row();

        if(!empty($last_inv) && isset($last_inv->invoice_number) && !empty($last_inv->invoice_number))
        {
            $expld = explode("/",$last_inv->invoice_number);
            if(isset($expld[2])) $inv_number = ((int) $expld[2]) + 1;
        }

        $inv_number = str_pad($inv_number, 7, "0", STR_PAD_LEFT);
        return "$kode/$date_inv/$inv_number";
    }

    protected function get_orders_info($order_id = 0)
    {
        return $this->db->limit(1)->get_where('orders', [
            'order_id' => (int) $order_id,
            'version' => 1,
        ])->first_row();
    }

    protected function get_customer_info($customer_id = 0)
    {
        return $this->db->limit(1)->get_where('customer', [
            'customer_id' => (int) $customer_id
        ])->first_row();
    }

    protected function get_customer_address($customer_id = 0, $customer_address_id = 0)
    {
        return $this->db->limit(1)->get_where('customer_address', [
            'customer_id' => (int) $customer_id,
            'customer_address_id' => (int) $customer_address_id
        ])->first_row();
    }

    protected function get_cart($order_id = 0)
    {
        return $this->db->get_where('orders_cart', [
            'order_id' => (int) $order_id
        ])->result();
    }
}
