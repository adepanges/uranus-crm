<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Package extends API_Controller {

	public function list()
	{
        $this->_response_json([
            'status' => 1,
            'message' => 'success'
        ]);
	}
}
