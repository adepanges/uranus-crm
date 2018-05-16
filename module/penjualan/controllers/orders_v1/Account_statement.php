<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account_statement extends Penjualan_Controller {

	public function get_useable()
	{
        $this->_restrict_access('account_statement_list', 'rest');
        $this->load->model('account_statement_model');

        $params = [
            'franchise_id' => $this->franchise->franchise_id,
            'payment_method_id' => (int) $this->input->post('payment_method_id'),
            'total_price' => $this->input->post('total_price'),
            'date_start' => $this->input->post('date_start'),
            'date_end' => $this->input->post('date_end')
        ];

        $params['date_start'] = !empty($params['date_start'])?$params['date_start']:date('Y-m-01');
        $params['date_end'] = !empty($params['date_end'])?$params['date_end']:date('Y-m-d');

        $this->account_statement_model->set_datatable_param($this->_datatable_param());
        $data = $this->account_statement_model->get_useable_datatable($params);
        $last_date_inv = $this->account_statement_model->get_last_date_inv($this->franchise->franchise_id)->first_row();

        $this->_response_json([
            'recordsFiltered' => $data['total'],
            'data' => $data['row'],
            'last_date_inv' => isset($last_date_inv->transaction_date)?$last_date_inv->transaction_date:''
        ]);
	}

    public function claim()
    {
        $this->_restrict_access('penjualan_orders_action_sale');
        $order_id = (int) $this->input->post('order_id');
        $account_statement_id = (int) $this->input->post('account_statement_id');
        // $this->load->model(['orders_model','orders_process_model','master_model','invoice_model']);
        $this->load->model(['invoice_model','orders_model','master_model','account_statement_model']);

        $res = $this->orders_model->get_byid_v1($order_id);
        $data = $res->first_row();
        $profile = $this->session->userdata('profile');

        if(!$res->num_rows() || !in_array($data->order_status_id, [6]))
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Orders telah di sale sebelumnya'
            ]);
        }

        $account_statement = $this->account_statement_model->get_byid($account_statement_id);
        if(
            empty($account_statement) ||
            (
                !empty($account_statement) &&
                isset($account_statement->claim) &&
                $account_statement->claim == 1
            )
        )
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Mutasi Rekeing tidak tersedia'
            ]);
        }

        $follow_up_status = $this->master_model->order_status(7)->first_row();

        $label_status = isset($follow_up_status->label)?$follow_up_status->label:'Sale';
        $order_status = [
            'order_status_id' => 7,
            'payment_method_id' => (int) $account_statement->payment_method_id,
            'order_status' => $label_status
        ];
        $order_process = [
            'order_id' => $order_id,
            'user_id' => $profile['user_id'],
            'order_status_id' => 7,
            'status' => $label_status,
            'notes' => "Payment diverifikasi oleh <b>{$profile['first_name']} {$profile['last_name']}</b>",
            'event_postback_status' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $res1 = $this->invoice_model->publish_v2($this->franchise->franchise_id, $order_id, $account_statement_id);
        // $res1 = $this->invoice_model->publish_v1($id, $paid_date, $invoice_number, $this->franchise->franchise_id);
        $res2 = $this->orders_model->upd($order_id, $order_status);
        $res3 = $this->orders_process_model->add($order_process);

        if($res1 && $res1 && $res2)
        {
            $this->_response_json([
                'status' => 1,
                'message' => 'Sukses, orders diteruskan ke tim logistik'
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
