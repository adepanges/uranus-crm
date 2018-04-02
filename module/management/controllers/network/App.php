<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends Management_Controller {

	public function index()
	{
        $this->_restrict_access('management_network');
        $this->_set_data([
            'title' => 'Network'
        ]);

        $this->blade->view('inc/management/network/app', $this->data);
	}

    public function save()
    {
        $network_id = (int) $this->input->post('network_id');
        if($network_id) $this->_restrict_access('management_network_upd', 'rest');
        else $this->_restrict_access('management_network_add', 'rest');

        $data = [
            'name' => $this->input->post('name'),
            'catch' => $this->input->post('catch'),
            'status' => (int) $this->input->post('status')
        ];

        $this->load->model('network_model');
        if(!$network_id)
        {
            // tambah
            $res = $this->network_model->add($data);
        }
        else
        {
            // ubah
            $res = $this->network_model->upd($data, $network_id);
        }

        if($res)
        {
            $this->_response_json([
                'status' => 1,
                'message' => 'Berhasil menyimpan data'
            ]);
        }
        else
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Gagal menyimpan data'
            ]);
        }
    }
}
