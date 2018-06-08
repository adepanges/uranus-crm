<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bca extends Keuangan_Controller {

	public function index()
	{
        $this->_restrict_access('account_statement');
        $this->load->model(['payment_method_model']);

        $this->_set_data([
            'title' => 'BCA Account Statement'
        ]);

        $this->blade->view('inc/keuangan/bank_statement/bca', $this->data);
	}
}
