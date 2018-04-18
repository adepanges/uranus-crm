<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends Management_Controller {

	public function index()
	{
        // $this->_restrict_access('management_product');
        $this->_set_data([
            'title' => 'Management Franchise'
        ]);

        $this->blade->view('inc/management/franchise/app', $this->data);
	}

    public function save()
    {
        $franchise_id = (int) $this->input->post('franchise_id');
        // if($franchise_id) $this->_restrict_access('management_product_upd', 'rest');
        // else $this->_restrict_access('management_product_add', 'rest');

        $data = [
            'code' => $this->input->post('code'),
            'name' => $this->input->post('name'),
            'nama_badan' => $this->input->post('nama_badan'),
            'address' => $this->input->post('address'),
            'status' => (int) $this->input->post('status')
        ];

        $this->load->model('franchise_model');
        if(!$franchise_id)
        {
            // tambah
            $res = $this->franchise_model->add($data);
        }
        else
        {
            // ubah
            $res = $this->franchise_model->upd($data, $franchise_id);
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
