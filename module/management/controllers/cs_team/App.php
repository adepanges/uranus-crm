<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends Management_Controller {

	public function index()
	{
        $this->_set_data([
            'title' => 'Management CS Team'
        ]);

        $this->blade->view('inc/management/cs_team/app', $this->data);
	}
}
