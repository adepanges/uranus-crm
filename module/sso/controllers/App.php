<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends Uranus_Controller {

	public function index()
	{
        redirect('auth/login');
	}
}
