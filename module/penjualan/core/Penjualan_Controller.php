<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(FCPATH.'resources/core/Dermeva_Controller.php');

class Penjualan_Controller extends Dermeva_Controller {
    function __construct()
    {
        parent::__construct();
        $this->_set_data([
            'orders_state' => $this->session->userdata('orders_state')
        ]);

        if(!$this->_is_sso_signed())
        {
            redirect($this->config->item('sso_link').'/auth/log/out');
        }

        $follow_up = $this->session->userdata('orders_follow_up');
        $check_condition = TRUE;
        if(
            ($this->uri->segment(2) == 'detail' && $this->uri->segment(3) == 'index') ||
            ($this->uri->segment(2) == 'follow_up' && $this->uri->segment(3) == 'confirm_buy') ||
            ($this->uri->segment(2) == 'follow_up' && $this->uri->segment(3) == 'cancel') ||
            ($this->uri->segment(2) == 'follow_up' && $this->uri->segment(3) == 'pending') ||
            ($this->uri->segment(2) == 'app' && $this->uri->segment(3) == 'update') ||
            ($this->uri->segment(2) == 'app' && $this->uri->segment(3) == 'update_shooping_info') ||
            ($this->uri->segment(2) == 'app' && $this->uri->segment(3) == 'del_addon_shopping_info') ||
            ($this->uri->segment(2) == 'app' && $this->uri->segment(3) == 'addon_shopping_info') ||
            ($this->uri->segment(1) == 'product' && $this->uri->segment(2) == 'get')
            )
        {
            $check_condition = FALSE;
        }

        if(!empty($follow_up) && $check_condition)
        {
            redirect('orders_v1/detail/index/'.$follow_up['order_id']);
        }
    }
}
