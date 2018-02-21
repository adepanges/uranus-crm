<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends Management_Controller {

	public function index()
	{
        redirect('cs_team');
	}
}
