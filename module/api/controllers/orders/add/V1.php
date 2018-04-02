<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class V1 extends API_Controller {

	public function index()
	{
        $json_data = json_decode($this->input->raw_input_stream);
        if(!empty($json_data))
        {
            $this->load->model(['orders_model','network_model','orders_process_model','customer_model']);

            $customer_info = [];
            $customer_address = [];
            $product_package_id = (int) isset($json_data->product_package_id)?$json_data->product_package_id:1;
            $order_code = $this->_generate_order_code_package($product_package_id);

            if(isset($json_data->customer_info))
            {
                $json_data->customer_info->telephone = isset($json_data->customer_info->telephone)?normalize_msisdn($json_data->customer_info->telephone):'';

                $customer_info = $this->customer_model->get_by_msisdn($json_data->customer_info->telephone);
                if(empty($customer_info)) {
                    $customer_info = $this->customer_model->add($json_data->customer_info);
                }
                else if(isset($customer_info->customer_id))
                {
                    $this->customer_model->upd($json_data->customer_info, $customer_info->customer_id);
                    $customer_info = $this->customer_model->get_byid($customer_info->customer_id);
                }
            }

            if(isset($json_data->customer_address))
            {
                $json_data->customer_address->customer_id = (isset($customer_info->customer_id))?$customer_info->customer_id:0;
                $customer_address = $this->customer_model->address_add($json_data->customer_address);
            }

            $orders = [
                'product_package_id' => $product_package_id,
                'customer_id' => (int) isset($customer_info->customer_id)?$customer_info->customer_id:0,
                'customer_address_id' => (int) isset($customer_address->customer_address_id)?$customer_address->customer_address_id:0,
                'payment_method_id' => (int) isset($json_data->payment_method_id)?$json_data->payment_method_id:1,
                'logistic_id' => (int) isset($json_data->logistic_id)?$json_data->logistic_id:1,
                'order_status_id' => 1,
                'logistics_status_id' => 1,
                'order_code' => $order_code,
                'call_method_id' => (int) isset($json_data->call_method_id)?$json_data->call_method_id:1,
                'created_at' => date('Y-m-d H:i:s'),
                'customer_info' => json_encode($customer_info),
                'customer_address' => json_encode($customer_address),
                'version' => 1
            ];

            $res = $this->orders_model->add($orders);
            $order_id = $this->db->insert_id();
            $res_cart = $this->orders_model->cart_add($order_id, $product_package_id);

            $order_process = [
                'order_id' => $order_id,
                'user_id' => 1,
                'order_status_id' => 1,
                'status' => 'New Orders',
                'notes' => "New Orders",
                'event_postback_status' => 0,
                'created_at' => $orders['created_at']
            ];
            $this->orders_process_model->add($order_process);

            if(
                isset($json_data->network) &&
                isset($json_data->network->id) && !empty($json_data->network->id) &&
                isset($json_data->network->catch) && !empty($json_data->network->catch)
            )
            {
                $network_id = (int) $json_data->network->id;
                $network = $this->network_model->get_byid($network_id)->first_row();
                if(!empty($network))
                {
                    $data_catch = (array) $json_data->network->catch;
                    $field_catch = explode(",",$network->catch);
                    $this->network_model->orders_add([
                        'order_id' => $order_id,
                        'network_id' => $network_id
                    ], $data_catch, $field_catch);
                }
            }

            if($res && $res_cart)
            {
                $this->_response_json([
                    'status' => 1,
                    'message' => 'success'
                ]);
            }
            else
            {
                $this->_response_json([
                    'status' => 0,
                    'message' => 'failed'
                ]);
            }
        }
        else
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'failed'
            ]);
        }
	}

    protected function _generate_order_code_package($product_package_id)
    {
        $this->load->model(['master_model','orders_model']);
        $res_package = $this->master_model->product_package($product_package_id)->first_row();
        $res_orders = $this->orders_model->get_last_order($product_package_id)->first_row();
        $seq_code = 1;
        $code = 'XX';
        if(!empty($res_orders) && isset($res_orders->order_code) && !empty($res_orders->order_code))
        {
            $epxl = explode('-', $res_orders->order_code);
            $no = isset($epxl[1])?$epxl[1]:'--------0001';
            $seq_code = ((int) substr($no, 8))+1;
        }
        $seq_code = str_pad($seq_code, 4, "0", STR_PAD_LEFT);
        if(!empty($res_package) && isset($res_package->code) && !empty($res_package->code))
        {
            $code = $res_package->code;
        }
        return $code.'-'.date('Ymd').$seq_code;
    }
}

// order_id
// customer_id
// customer_address_id
// payment_method_id
// logistic_id
// order_status_id
// logistics_status_id
// call_method_id
// order_status
// logistics_status
// shipping_code
// call_method
// order_code
// customer_info
// customer_address
// total_price
// created_at
// version
