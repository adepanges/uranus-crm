<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends Uranus_Controller {

    function __construct()
    {
        parent::__construct();
    }

	public function index()
	{
        $this->_set_data([
            'title' => 'Management User'
        ]);
        $this->blade->view('inc/sso/user/app', $this->data);
	}
}
