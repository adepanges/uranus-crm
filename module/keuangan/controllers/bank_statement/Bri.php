<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bri extends Keuangan_Controller {

    public function index()
	{
        $this->_restrict_access('account_statement');
        $this->load->model(['payment_method_model']);

        $this->_set_data([
            'title' => 'BRI Account Statement'
        ]);

        $this->blade->view('inc/keuangan/bank_statement/bri', $this->data);
	}
}
