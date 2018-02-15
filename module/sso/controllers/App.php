<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends SSO_Controller {

	public function index()
	{
        redirect('auth/login');
	}
}
