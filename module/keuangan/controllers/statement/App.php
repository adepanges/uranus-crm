<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends Keuangan_Controller {

	public function index()
	{
        if(!$this->_is_sso_signed())
        {
            redirect($this->config->item('sso_link').'/auth/log/out');
        }
	}
}
