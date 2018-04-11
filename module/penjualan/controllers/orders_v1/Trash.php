<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trash extends Penjualan_Controller {

    public function index()
    {
        $this->_restrict_access('penjualan_orders_trash');
        $this->session->set_userdata('orders_state', 'orders_v1/trash');
        $this->_set_data([
            'title' => 'Trash Orders'
        ]);

        $this->blade->view('inc/penjualan/orders/trash_v1', $this->data);
    }

    function get()
    {
        $this->_restrict_access('penjualan_orders_trash_list', 'rest');
        $this->load->model(['trash_orders_model']);
        $this->trash_orders_model->set_datatable_param($this->_datatable_param());
        $data = $this->trash_orders_model->get_datatable();

        $this->_response_json([
            'recordsFiltered' => $data['total'],
            'data' => $data['row']
        ]);
    }

    function del($order_id = '')
    {
        $this->_restrict_access('penjualan_orders_trash_delete', 'rest');
        $this->load->model(['trash_orders_model']);
        if(empty($order_id)) $order_id = $this->input->post('order_id');
        $order_id = explode(',',base64_decode($order_id));
        $res = $this->trash_orders_model->del($order_id);
        if($res)
        {
            $this->_response_json([
                'status' => 1,
                'message' => 'Berhasil menghapus orders'
            ]);
        }
        else
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Gagal menghapus orders'
            ]);
        }
    }

    function pulihkan($order_id)
    {
        $this->_restrict_access('penjualan_orders_trash_pulihkan', 'rest');
        $this->load->model(['trash_orders_model']);
        $order_id = (int) $order_id;
        $res = $this->trash_orders_model->pulihkan($order_id);
        if($res)
        {
            redirect($this->session->userdata('orders_state'));
        }
        else redirect('orders_v1/follow_up/index/'.$order_id.'?FAIL');
    }
}
