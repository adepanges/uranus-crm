<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends Management_Controller {

	public function index()
	{
        $this->_restrict_access('management_package_product');
        $this->_set_data([
            'title' => 'Management Package'
        ]);

        $this->blade->view('inc/management/package/app', $this->data);
	}

    public function save()
    {
        $product_package_id = (int) $this->input->post('product_package_id');
        if($product_package_id) $this->_restrict_access('management_package_product_upd', 'rest');
        else $this->_restrict_access('management_package_product_add', 'rest');

        $data = [
            'code' => $this->input->post('code'),
            'name' => $this->input->post('name'),
            'price_type' => $this->input->post('price_type'),
            'price' => $this->input->post('price'),
            'status' => (int) $this->input->post('status')
        ];

        $this->load->model('package_model');
        if(!$product_package_id)
        {
            // tambah
            $res = $this->package_model->add($data);
        }
        else
        {
            // ubah
            $res = $this->package_model->upd($data, $product_package_id);
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
