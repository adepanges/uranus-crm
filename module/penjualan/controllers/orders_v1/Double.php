<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Double extends Penjualan_Controller {

    public function index()
    {
        $this->_restrict_access('penjualan_orders_double');
        $this->session->set_userdata('orders_state', 'orders_v1/double');
        $this->_set_data([
            'title' => 'Double Orders'
        ]);

        $this->blade->view('inc/penjualan/orders/double_v1', $this->data);
    }

    public function get()
	{
        $this->_restrict_access('penjualan_orders_double_list', 'rest');
        $this->load->model('double_orders_model');

        $this->double_orders_model->set_datatable_param($this->_datatable_param());
        $double_orders = $this->double_orders_model->get_datatable();

        $this->_response_json([
            'recordsFiltered' => $double_orders['total'],
            'data' => $double_orders['row']
        ]);
	}

    public function get_follow_up()
	{
        $this->_restrict_access('penjualan_orders_double_list', 'rest');
        $this->load->model('double_orders_model');

        $this->double_orders_model->set_datatable_param($this->_datatable_param());
        $double_orders = $this->double_orders_model->get_follow_up_datatable($this->profile['user_id']);

        $this->_response_json([
            'recordsFiltered' => $double_orders['total'],
            'data' => $double_orders['row']
        ]);
	}

    function detail($id)
    {
        $this->_restrict_access('penjualan_orders_double_detail', 'rest');
        $id = (int) $id;
        $this->load->model('double_orders_model');

        $orders = $this->double_orders_model->get_orders($id);
        if($orders->num_rows() == 0)
        {
            $this->double_orders_model->solve($id);
            redirect($this->session->userdata('orders_state'));
        }

        $this->_set_data([
            'title' => 'Double Orders',
            'orders_double' => $this->double_orders_model->get_byid($id)->first_row(),
            'orders' => $orders->result()
        ]);

        $this->blade->view('inc/penjualan/orders/double_detail_v1', $this->data);
    }

    function trash($orders_double_id)
    {
        $this->_restrict_access('penjualan_orders_to_trash', 'rest');
        $orders_double_id = (int) $orders_double_id;
        $this->load->model(['double_orders_model','orders_model']);

        $double_orders = $this->double_orders_model->get_byid($orders_double_id)->first_row();
        $check = $this->orders_model->get_active_orders_by_customer_id($double_orders->customer_id)->num_rows();

        if(!$check)
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Orders belum ada yang dipulihkan'
            ]);
        }

        $orders = $this->double_orders_model->get_orders($orders_double_id)->result();
        $order_id = [];
        foreach ($orders as $key => $value) {
            $order_id[] = $value->order_id;
        }

        $res = TRUE;
        if(!empty($order_id)) $res = $this->orders_model->trash($order_id);
        $res2 = $this->double_orders_model->solve($orders_double_id);

        if($res && $res2)
        {
            $this->_response_json([
                'status' => 1,
                'message' => 'Berhasil membuang semua orders'
            ]);
        }
        else
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Gagal membuang semua orders'
            ]);
        }
    }

    function pulihkan($id)
    {
        $this->_restrict_access('penjualan_orders_double_pulihkan', 'rest');
        $id = (int) $id;
        $this->load->model('double_orders_model');

        $res = $this->double_orders_model->pulihkan($id);

        if($res)
        {
            $this->_response_json([
                'status' => 1,
                'message' => 'Berhasil memulihkan orders'
            ]);
        }
        else
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Gagal memulihkan orders'
            ]);
        }
    }
}
