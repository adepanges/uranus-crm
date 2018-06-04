<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_model extends Penjualan_Model {

    function publish_v2($franchise_id, $order_id, $account_statement_id)
    {
        $this->load->model(['account_statement_model','orders_process_model']);
        $account_statement = $this->account_statement_model->get_byid($account_statement_id);
        $confirm_buy = $this->orders_process_model->get_by_status($order_id, 5);

        $billed_date = (isset($confirm_buy->created_at) && !empty($confirm_buy->created_at))?$confirm_buy->created_at:$account_statement->transaction_date;

        $orders = $this->get_orders_info($order_id);
        $orders_cart = $this->get_cart($orders->order_id);

        $invoice_data = [
            'order_id' => $orders->order_id,
            'account_statement_id' => $account_statement->account_statement_id,
            'franchise_id' => $franchise_id,
            'customer_id' => $orders->customer_id,
            'customer_address_id' => $orders->customer_address_id,
            'logistic_id' => $orders->logistic_id,
            'logistic_name' =>  $orders->logistic_name,
            'invoice_number' => $account_statement->generated_invoice,
            'order_code' => $orders->order_code,
            'customer' => $orders->customer_info,
            'customer_address' => $orders->customer_address,
            'order_cart' => json_encode($orders_cart),
            'total_price' => $orders->total_price,
            'transaction_amount' => $account_statement->transaction_amount,
            'payment_method' => $orders->payment_method,
            'billed_date' => $billed_date,
            'paid_date' => $account_statement->transaction_date,
            'publish_date' =>  date('Y-m-d H:i:s'),
            'version' => 1
        ];

        $this->account_statement_model->upd(['claim' => 1], $account_statement_id);
        return $this->db->insert('orders_invoices', $invoice_data);
    }

    function publish_v1($order_id = 0, $paid_time = '', $invoice_number = '', $franchise_id = 0)
    {
        if(empty($paid_time))
        {
            $paid_time = date('Y-m-d H:i:s');
        }

        if(empty($invoice_number))
        {
            $invoice_number = $this->create_invoice_number_v1($paid_time);
        }

        $orders = $this->get_orders_info($order_id);
        $orders_cart = $this->get_cart($orders->order_id);

        $invoice_data = [
            'order_id' => $orders->order_id,
            'franchise_id' => $franchise_id,
            'customer_id' => $orders->customer_id,
            'customer_address_id' => $orders->customer_address_id,
            'logistic_id' => $orders->logistic_id,
            'logistic_name' =>  $orders->logistic_name,
            'invoice_number' => $invoice_number,
            'order_code' => $orders->order_code,
            'customer' => $orders->customer,
            'customer_address' => $orders->customer_address,
            'order_cart' => json_encode($orders_cart),
            'total_price' => $orders->total_price,
            'payment_method' => $orders->payment_method,
            'billed_date' => $paid_time,
            'paid_date' => $paid_time,
            'publish_date' =>  date('Y-m-d H:i:s'),
            'version' => 1
        ];

        return $this->db->insert('orders_invoices', $invoice_data);
    }

    function upd($data, $order_invoice_id, $order_id)
    {

        $orders = $this->get_orders_info($order_id);
        $customer = $this->get_customer_info($orders->customer_id, $orders->customer_phonenumber_id);
        $customer_address = $this->get_customer_address($orders->customer_id, $orders->customer_address_id);
        $orders_cart = $this->get_cart($orders->order_id);

        $this->db->where('order_invoice_id', $order_invoice_id);
        return $this->db->update('orders_invoices', [
            'invoice_number' => $data['invoice_number'],
            'customer' => json_encode($customer),
            'customer_address' => json_encode($customer_address),
            'logistic_id' => $orders->logistic_id,
            'logistic_name' => $orders->logistic_name,
            'order_cart' => json_encode($orders_cart),
            'total_price' => $orders->total_price,
            'payment_method' => $orders->payment_method,
            'paid_date' => $data['paid_date'],
        ]);
    }

    function get_by_inv_numb($invoice_number, $order_invoice_id = 0, $franchise_id = 0)
    {
        if($order_invoice_id != 0)
        {
            $this->db->where('order_invoice_id !=', $order_invoice_id);
        }

        if($franchise_id != 0)
        {
            $this->db->where('franchise_id', $franchise_id);
        }

        return $this->db->get_where('orders_invoices', [
            'invoice_number' => $invoice_number
        ]);
    }

    protected function create_invoice_number_v1($time)
    {
        $time = strtotime($time);
        $kode = 'DKI';
        $date_inv = date('Ymd', $time);
        $inv_number = 1;

        $last_inv = $this->db->query('SELECT invoice_number
            FROM orders_invoices
            WHERE YEAR(publish_date) = ?
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
        $sql = "SELECT a.*, b.name as payment_method, c.name as logistic_name
        FROM orders a
        LEFT JOIN master_payment_method b ON a.payment_method_id = b.payment_method_id
        LEFT JOIN master_logistics c ON a.logistic_id = c.logistic_id
        WHERE a.order_id = ? AND a.version = 1 LIMIT 1";
        return $this->db->query($sql, [
            'order_id' => (int) $order_id
        ])->first_row();
    }

    protected function get_customer_info($customer_id = 0, $customer_phonenumber_id = 0)
    {
        return $this->db->query('SELECT a.*, b.phonenumber AS telephone
            FROM customer a
            LEFT JOIN customer_phonenumber b ON a.customer_id = b.customer_id
            WHERE a.customer_id = ? AND b.customer_phonenumber_id = ?', [
                (int) $customer_id, (int) $customer_phonenumber_id
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

    function get_invoice($id = 0)
    {
        $this->db->where_in('order_id', $id);
        $res = $this->db->limit(1)->get('orders_invoices');
        return $res;
    }
}
