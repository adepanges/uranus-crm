<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Get extends Penjualan_Controller {

    public function index($flag = 'new')
    {
        $this->_restrict_access('penjualan_orders_list');
        $this->load->model('orders_model');

        $params = [
            'order_status_id' => 1,
            'user_id' => $this->profile['user_id'],
            'role_id' => $this->data['role_active']->role_id,
            'tim_leader' => $this->session->userdata('tim_leader'),
            'date_start' => $this->input->post('date_start'),
            'date_end' => $this->input->post('date_end'),
            'filter_sale' => $this->input->post('filter_sale')
        ];
        $status = [
            'new' => 1,
            'pending' => 3,
            'cancel' => 4,
            'confirm_buy' => 5,
            'verify' => 6,
            'sale' => 7,
            'assigned' => 10
        ];
        $only_see_own = FALSE;

        if(isset($status[$flag])) $params['order_status_id'] = (int) $status[$flag];
        if(
            isset($this->role_active['role_id']) &&
            (
                (
                    // CS
                    $this->role_active['role_id'] == 5 &&
                    in_array($params['order_status_id'], [2,3,4,5,6,7,10])
                )
                ||
                (
                    // finance
                    $this->role_active['role_id'] == 3 &&
                    in_array($params['order_status_id'], [7])
                )
            )
        )
        {
            $only_see_own = TRUE;
        }

        $params['date_start'] = !empty($params['date_start'])?$params['date_start']:date('Y-m-01');
        $params['date_end'] = !empty($params['date_end'])?$params['date_end']:date('Y-m-d');

        $this->orders_model->set_datatable_param($this->_datatable_param());
        $data = $this->orders_model->get_datatable_v1($params, $only_see_own);

        $this->_response_json([
            'recordsFiltered' => $data['total'],
            'data' => $data['row']
        ]);
    }

    function badge()
    {
        $this->load->model('badge_model');
        $this->badge_model->set_viewer_profile($this->profile);
        $this->badge_model->set_viewer_tim($this->session->userdata('tim_leader'));
        $this->badge_model->set_viewer_role($this->role_active);

        $this->_response_json([
            'count_new' => (int) $this->badge_model->new()->count,
            'count_assigned' => (int) $this->badge_model->assigned()->count,
            'count_double' => (int) $this->badge_model->double()->count,
            'count_pending' => (int) $this->badge_model->pending()->count,
            'count_confirm_buy' => (int) $this->badge_model->confirm_buy()->count,
            'count_verify' => (int) $this->badge_model->verify()->count,
            'count_sale' => (int) $this->badge_model->sale()->count,
            'count_cancel' => (int) $this->badge_model->cancel()->count,
            'count_trash' => (int) $this->badge_model->trash()->count
        ]);
    }
}
