<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends API_Controller {

	public function index()
	{
        $this->_response_json([
            'status' => 1,
            'message' => 'success'
        ]);
	}
}
