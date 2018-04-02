<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends Portal_Controller {

	public function index()
	{
        $this->_set_data([
            'title' => 'Portal',
            'list_module' => $this->session->userdata('module')
        ]);
        
        $this->blade->view('inc/portal/app', $this->data);
	}
}
