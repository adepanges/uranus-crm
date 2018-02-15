<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends SSO_Controller {

	public function index()
	{
        $this->blade->view('inc/sso/auth/login', [
            'title' => $this->config->item('app_name')
        ]);
	}
}
