<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends Logistik_Controller {

    public function index()
    {
        $this->_restrict_access('logistik_packing_notyet');
        $this->session->set_userdata('packing_state', 'packing_v1/app');
        $this->_set_data([
            'title' => 'Pesanan Belum Packing'
        ]);

        $this->blade->view('inc/logistik/packing/notyet_v1', $this->data);
    }

    public function alredy($id = '')
    {
        $this->_restrict_access('logistik_packing_action_alredy');
        if(empty($id)) redirect($this->session->userdata('packing_state'));

        $id = explode(",",base64_decode($id));

        foreach ($id as $key => $value) {
            $value = (int) $value;
            $this->_alredy_pack($value);
        }

        redirect($this->session->userdata('packing_state'));

    }

    protected function _alredy_pack($id)
    {
        $id = (int) $id;
        $this->load->model(['orders_model','master_model','logistics_process_model']);
        $res = $this->orders_model->get_byid_v1($id);
        $data = $res->first_row();
        $profile = $this->session->userdata('profile');

        if(!$res->num_rows() || $data->order_status_id != 7 || $data->logistics_status_id != 1)
        {
            return false;
        }

        $oders_status = $this->master_model->order_status(8)->first_row();
        $logistics_status = $this->master_model->logistics_status(3)->first_row();

        $oders_status = isset($oders_status->label)?$oders_status->label:'Logistics';
        $label_logistics_status = isset($logistics_status->label)?$logistics_status->label:'Sudah di Packing';
        $order_status = [
            'order_status_id' => 8,
            'logistics_status_id' => 3,
            'order_status' => $oders_status,
            'logistics_status' => $label_logistics_status
        ];
        $logistik_process = [
            'order_id' => $id,
            'user_id' => $profile['user_id'],
            'logistics_status_id' => 3,
            'status' => $label_logistics_status,
            'notes' => "Pesanan sudah sudah di packing",
            'created_at' => date('Y-m-d H:i:s')
        ];

        $res1 = $this->orders_model->upd($id, $order_status);
        $res2 = $this->logistics_process_model->add($logistik_process);
        return true;
    }
}
