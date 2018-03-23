<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends Penjualan_Controller {

    public function index()
    {
        $this->_restrict_access('penjualan_orders_new');
        $this->session->set_userdata('orders_state', 'orders_v1/app');
        $this->_set_data([
            'title' => 'New Orders'
        ]);

        $this->blade->view('inc/penjualan/orders/app_v1', $this->data);
    }

    function get_byid($id = 0)
    {
        $id = (int) $id;
        $this->load->model('orders_model');
        $res = $this->orders_model->get_byid_v1($id);
        $data = $res->first_row();
        $this->_response_json([
            'data' => $data
        ]);
    }

    function update()
    {
        $this->load->model(['orders_model', 'customer_model']);
        $order_id = (int) $this->input->post('order_id');
        $customer_id = (int) $this->input->post('customer_id');
        $customer_address_id = (int) $this->input->post('customer_address_id');

        $customer_info = [
            'full_name' => $this->input->post('full_name'),
            'telephone' => $this->input->post('telephone')
        ];
        $customer_address = [
            'address' => $this->input->post('address'),
            'provinsi' => $this->input->post('provinsi'),
            'provinsi_id' => $this->input->post('provinsi_id'),
            'kabupaten' => $this->input->post('kabupaten'),
            'kabupaten_id' => $this->input->post('kabupaten_id'),
            'kecamatan' => $this->input->post('kecamatan'),
            'kecamatan_id' => $this->input->post('kecamatan_id'),
            'desa_kelurahan' => $this->input->post('desa_kelurahan'),
            'desa_id' => $this->input->post('desa_id'),
            'postal_code' => $this->input->post('postal_code')
        ];
        $orders = [
            'logistic_id' => (int) $this->input->post('logistic_id'),
            'call_method_id' => (int) $this->input->post('call_method_id'),
            'customer_info' => json_encode($customer_info),
            'customer_address' => json_encode($customer_address)
        ];
        $res1 = $this->orders_model->upd($order_id, $orders);
        $res2 = $this->customer_model->upd($customer_id, $customer_info);
        $res3 = $this->customer_model->upd_address($customer_address_id, $customer_address);

        if($res1 && $res2 && $res3)
        {
            $this->_response_json([
                'status' => 1,
                'message' => 'Berhasil mengubah data'
            ]);
        }
        else
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Gagal mengubah data'
            ]);
        }
    }

    public function follow_up($id)
    {
        $this->_restrict_access('penjualan_orders_action_follow_up');
        $id = (int) $id;
        $this->load->model(['orders_model','orders_process_model','master_model']);
        $res = $this->orders_model->get_byid_v1($id);
        $data = $res->first_row();
        $profile = $this->session->userdata('profile');

        if(!$res->num_rows() || !in_array($data->order_status_id, [1,3,4,5])) redirect('orders_v1');

        $follow_up_status = $this->master_model->order_status(2)->first_row();

        $label_status = isset($follow_up_status->label)?$follow_up_status->label:'Follow Up';
        $order_status = [
            'order_status_id' => 2,
            'order_status' => $label_status
        ];
        $order_process = [
            'order_id' => $id,
            'user_id' => $profile['user_id'],
            'order_status_id' => 2,
            'status' => $label_status,
            'notes' => "Pesanan sedang di $label_status oleh <b>{$profile['first_name']} {$profile['last_name']}</b>",
            'event_postback_status' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $res1 = $this->orders_model->upd($id, $order_status);
        $res2 = $this->orders_process_model->add($order_process);

        if($res1 && $res2)
        {
            $this->session->set_userdata('orders_follow_up', ['order_id' => $id, 'created_at' => $order_process['created_at']]);
            redirect('orders_v1/follow_up/index/'.$id);
        }
        else redirect($this->session->userdata('orders_state'));
    }

    function update_shooping_info()
    {
        $this->load->model(['orders_model']);
        $order_id = (int) $this->input->post('order_id');
        $product_package_id = (int) $this->input->post('product_package_id');

        $orders = [
            'payment_method_id' => (int) $this->input->post('payment_method_id')
        ];
        $res1 = $this->orders_model->upd($order_id, $orders);
        $res2 = $this->orders_model->clear_cart_package($order_id);
        $res3 = $this->orders_model->upd_cart_package($order_id, $product_package_id);
        $res4 = $this->orders_model->upd($order_id, [
            'total_price' => $this->orders_model->get_latest_price_cart($order_id)
        ]);

        if(true)
        {
            $this->_response_json([
                'status' => 1,
                'message' => 'Berhasil mengubah data'
            ]);
        }
        else
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Gagal mengubah data'
            ]);
        }
    }
}
