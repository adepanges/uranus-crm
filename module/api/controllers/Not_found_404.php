<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Not_found_404 extends API_Controller {

	public function index()
	{
        header("HTTP/1.0 404 Not Found");
        $this->_response_json([
            'status' => 404,
            'message' => 'api tidak tersedia'
        ]);
	}
}
