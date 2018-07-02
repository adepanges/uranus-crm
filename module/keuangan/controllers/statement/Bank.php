<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank extends Keuangan_Controller {

	public function bca()
	{
        $this->_restrict_access('account_statement');
        $this->load->model(['payment_method_model']);

        $this->_set_data([
            'title' => 'Account Statement',
            'account' => $this->payment_method_model->get_active()->result()
        ]);

        $this->blade->view('inc/keuangan/account_statement/app', $this->data);
	}
}
