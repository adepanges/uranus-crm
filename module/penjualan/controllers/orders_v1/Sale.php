<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sale extends Penjualan_Controller {

    public function index()
    {
        $this->_restrict_access('penjualan_orders_sale');
        $this->session->set_userdata('orders_state', 'orders_v1/sale');

        $this->load->model('cs_model');

        $tl = $this->session->userdata('tim_leader');
        $team_cs_id = 0;
        if(!empty($tl) && isset($tl->team_cs_id))
        {
            $team_cs_id = $tl->team_cs_id;
        }

        $this->_set_data([
            'title' => 'Sale Orders',
            'list_cs' => $this->cs_model->get_active([
                'role_id' => $this->role_active['role_id'],
                'team_cs_id' => $team_cs_id
            ])->result()
        ]);

        $this->blade->view('inc/penjualan/orders/sale_v1', $this->data);
    }

    public function upd_invoice()
    {
        if(
            !in_array($this->role_active['role_id'], [1,2,3])
        )
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Gagal'
            ]);
        }

        $order_id = (int) $this->input->post('order_id');
        $order_invoice_id = (int) $this->input->post('order_invoice_id');
        $invoice_number = $this->input->post('invoice_number');
        $paid_date = $this->input->post('paid_date');

        $this->load->model(['orders_model','invoice_model']);
        $res = $this->orders_model->get_byid_v1($order_id);
        $data = $res->first_row();

        if(!$res->num_rows() || (isset($data->order_status_id) && $data->order_status_id < 7 ))
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Gagal'
            ]);
        }

        if(
            empty($this->input->post('invoice_number'))
        )
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Nomor Invoice Tidak Diperbolehkan Kosong'
            ]);
        }

        if(
            !empty($invoice_number) &&
            $this->invoice_model->get_by_inv_numb($invoice_number, $order_invoice_id)->num_rows() > 0
        )
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Nomor Invoice sudah digunakan'
            ]);
        }

        $res = $this->invoice_model->upd([
            'invoice_number' => $invoice_number,
            'paid_date' => !empty($paid_date)?$paid_date:date('Y-m-d')
        ], $order_invoice_id, $order_id);

        if($res)
        {

            $this->session->set_userdata('orders_follow_up', '');
            $this->_response_json([
                'status' => 1,
                'message' => 'Sukses, invoice telah dirubah'
            ]);
        }
        else
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Gagal'
            ]);
        }
    }
}
