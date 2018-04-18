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

    function trash($id)
    {
        $this->_restrict_access('penjualan_orders_to_trash');
        $id = (int) $id;
        $this->load->model('orders_model');

        $res = $this->orders_model->trash($id);

        if($res)
        {
            $this->_response_json([
                'status' => 1,
                'message' => 'Berhasil membuang orders'
            ]);
        }
        else
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Gagal membuang orders'
            ]);
        }
    }

    function update()
    {
        $this->_restrict_access('penjualan_orders_update_customer_info', 'rest');

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

        if(!$res->num_rows() || !in_array($data->order_status_id, [1,3])) redirect('orders_v1');
        if($data->order_status_id > 1)
        {
            $check_followup_cs = $this->orders_model->validate_followup_cs($data->order_id, $this->profile['user_id']);
            if($check_followup_cs->num_rows() == 0)
            {
                redirect('orders_v1');
            }
        }

        $follow_up_status = $this->master_model->order_status(2)->first_row();

        $label_status = isset($follow_up_status->label)?$follow_up_status->label:'Follow Up';
        $order_status = [
            'franchise_id' => $this->franchise->franchise_id,
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

    function del_addon_shopping_info($id = 0, $cart_id = 0)
    {
        $this->_restrict_access('penjualan_orders_update_shopping_info', 'rest');
        $this->load->model(['orders_model']);
        $order_id = (int) $id;
        $cart_id = (int) $cart_id;

        $res1 = $this->orders_model->del_by_cart_id($cart_id);
        $res2 = $this->orders_model->upd($order_id, [
            'total_price' => $this->orders_model->get_latest_price_cart($order_id)
        ]);

        if($res1 && $res2)
        {
            redirect('orders_v1/detail/index/'.$id.'?SUCCESS');
        }
        else redirect('orders_v1/detail/index/'.$id.'?FAIL');
    }

    function addon_shopping_info()
    {
        $this->_restrict_access('penjualan_orders_update_shopping_info', 'rest');
        $this->load->model(['orders_model']);
        $order_id = (int) $this->input->post('order_id');

        $qty = (int) !empty($this->input->post('qty'))?$this->input->post('qty'):1;

        $params = [
            'order_id' => $order_id,
            'product_id' => !empty($this->input->post('product_id'))?$this->input->post('product_id'):NULL,
            'product_merk' => !empty($this->input->post('merk'))?$this->input->post('merk'):NULL,
            'product_name' => !empty($this->input->post('name'))?$this->input->post('name'):$this->input->post('name_other'),
            'qty' => $qty,
            'is_package' => 0,
            'price' => ((int) $this->input->post('price')) * $qty,
            'weight' => (int) $this->input->post('weight'),
            'price_type' => 'RETAIL',
            'version' => 1,
        ];

        $res1 = $this->orders_model->addon_cart($params);
        $res2 = $this->orders_model->upd($order_id, [
            'total_price' => $this->orders_model->get_latest_price_cart($order_id)
        ]);

        if($res1 && $res2)
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

    function update_shooping_info()
    {
        $this->_restrict_access('penjualan_orders_update_shopping_info', 'rest');

        $this->load->model(['orders_model']);
        $order_id = (int) $this->input->post('order_id');
        $product_package_id = (int) $this->input->post('product_package_id');
        $qty = (int) $this->input->post('qty');
        if($qty == 0) $qty = 1;

        $orders = [
            'payment_method_id' => (int) $this->input->post('payment_method_id')
        ];

        $res1 = $this->orders_model->upd($order_id, $orders);
        $res2 = $this->orders_model->clear_cart_package($order_id);
        $res3 = $this->orders_model->upd_cart_package($order_id, $product_package_id, $qty);
        $res4 = $this->orders_model->upd($order_id, [
            'total_price' => $this->orders_model->get_latest_price_cart($order_id)
        ]);

        if($res1 && $res2 && $res3 && $res4)
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
