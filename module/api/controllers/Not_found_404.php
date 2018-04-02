<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Not_found_404 extends API_Controller {

	public function index()
	{
        $this->_response_json([
            'status' => 404,
            'message' => 'api tidak tersedia'
        ]);
	}
}
