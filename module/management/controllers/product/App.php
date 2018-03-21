<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends Management_Controller {

	public function index()
	{
        $this->_restrict_access('management_product');
        $this->_set_data([
            'title' => 'Management Product'
        ]);

        $this->blade->view('inc/management/product/app', $this->data);
	}

    public function save()
    {
        $product_id = (int) $this->input->post('product_id');
        if($product_id) $this->_restrict_access('management_product_upd', 'rest');
        else $this->_restrict_access('management_product_add', 'rest');

        $data = [
            'code' => $this->input->post('code'),
            'merk' => $this->input->post('merk'),
            'name' => $this->input->post('name'),
            'weight' => $this->input->post('weight'),
            'price' => $this->input->post('price'),
            'status' => (int) $this->input->post('status')
        ];

        $this->load->model('product_model');
        if(!$product_id)
        {
            // tambah
            $res = $this->product_model->add($data);
        }
        else
        {
            // ubah
            $res = $this->product_model->upd($data, $product_id);
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
