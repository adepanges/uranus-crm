<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends Report_Controller {

	public function index()
	{
        $this->_restrict_access('report_simple');
        $this->_set_data([
            'title' => 'Laporan'
        ]);

        $this->blade->view('inc/report/simple/app', $this->data);
	}
}
