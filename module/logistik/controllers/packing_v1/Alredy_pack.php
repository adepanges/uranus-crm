<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alredy_pack extends Logistik_Controller {

    public function index()
    {
        $this->_restrict_access('logistik_packing_alredy');
        $this->session->set_userdata('packing_state', 'packing_v1/alredy_pack');
        $this->_set_data([
            'title' => 'Pesanan Sudah di Packing'
        ]);

        $this->blade->view('inc/logistik/packing/alredy_v1', $this->data);
    }

    public function pickup($id)
    {
        $this->_restrict_access('logistik_packing_action_pickup');
        if(empty($id)) redirect($this->session->userdata('packing_state'));
        $id = explode(",",base64_decode($id));

        foreach ($id as $key => $value) {
            $value = (int) $value;
            $this->_pickup($value);
        }

        redirect($this->session->userdata('packing_state'));
    }

    protected function _pickup($id)
    {
        $id = (int) $id;
        $this->load->model(['orders_model','master_model','logistics_process_model']);
        $res = $this->orders_model->get_byid_v1($id);
        $data = $res->first_row();
        $profile = $this->session->userdata('profile');

        if(!$res->num_rows() || $data->order_status_id != 8 || $data->logistics_status_id != 3)
        {
            return false;
        }
        $logistics_status = $this->master_model->logistics_status(4)->first_row();

        $label_logistics_status = isset($logistics_status->label)?$logistics_status->label:'Sudah di Pickup';

        $order_status = [
            'logistics_status_id' => 4,
            'logistics_status' => $label_logistics_status
        ];
        $logistik_process = [
            'order_id' => $id,
            'user_id' => $profile['user_id'],
            'logistics_status_id' => 4,
            'status' => $label_logistics_status,
            'notes' => "Pesanan sudah sudah di pickup oleh ekspedisi",
            'created_at' => date('Y-m-d H:i:s')
        ];
        $res1 = $this->orders_model->upd($id, $order_status);
        $res2 = $this->logistics_process_model->add($logistik_process);

        return true;
    }
}
