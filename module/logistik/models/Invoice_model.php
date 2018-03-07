<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_model extends Logistik_Model {

    function publish_v1($order_id = 0)
    {
        $invoice_data = [
            'order_id' => '',
            'customer_id' => '',
            'order_code' => '',
            'customer' => '',
            'customer_address_id' => '',
            'customer_address' => '',
            'order_cart' => '',
            'billed_date' => '',
            'paid_date' => '',
            'version' => '',
        ]
    }

    protected function create_invoice_number_v1()
    {

    }

    protected function get_customer_info()
    {

    }

    protected function get_customer_address()
    {

    }

    protected function get_cart()
    {

    }
}
