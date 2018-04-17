<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_list extends Management_Controller {

	public function index($id = 0)
	{
        $this->_restrict_access('management_package_product_detail');
        $id = (int) $id;

        $this->load->model(['package_model','package_product_list_model']);
        $package = $this->package_model->get_byid($id);

        if(empty($package)) redirect('package');

        $this->_set_data([
            'title' => 'Package - Product List',
            'package' => $package
        ]);

        $this->blade->view('inc/management/package/product_list', $this->data);
	}

    function get($id = 0)
    {
        $this->_restrict_access('management_package_product_detail', 'rest');
        $id = (int) $id;

        $this->load->model('package_product_list_model');
        $this->package_product_list_model->set_datatable_param($this->_datatable_param());
        $member_data = $this->package_product_list_model->get_datatable($id);

        $this->_response_json([
            'recordsFiltered' => $member_data['total'],
            'data' => $member_data['row']
        ]);
    }

    public function get_byid($id = 0)
    {
        $this->_restrict_access('management_package_product_detail', 'rest');
        $data = (object) [];
        $id = (int) $id;

        if($id)
        {
            $this->load->model('package_product_list_model');
            $data = $this->package_product_list_model->get_byid($id);
        }
        $this->_response_json($data);
    }

    public function save()
    {
        $product_package_list_id = (int) $this->input->post('product_package_list_id');
        $this->_restrict_access('management_package_product_manage', 'rest');

        $data = [
            'product_package_id' => (int) $this->input->post('product_package_id'),
            'qty' => (int) $this->input->post('qty'),
            'price' => $this->input->post('price'),
            'status' => (int) $this->input->post('status')
        ];

        $this->load->model('package_product_list_model');
        if(!$product_package_list_id)
        {
            // add
            if(!empty($this->input->post('bulk')) && is_array($this->input->post('bulk')))
            {
                foreach ($this->input->post('bulk') as $key => $value) {
                    $pdl = (array) json_decode(base64_decode($value));
                    $pdl['product_package_id'] = $data['product_package_id'];
                    $pdl['qty'] = 1;
                    $res = $this->package_product_list_model->add($pdl);
                }
            }
            else
            {
                $res = $this->package_product_list_model->add($data);
            }
        }
        else
        {
            // ubah
            $res = $this->package_product_list_model->upd($data, $product_package_list_id);
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

    function del($product_package_list_id = 0)
    {
        $this->_restrict_access('management_package_product_manage', 'rest');
        $product_package_list_id = (int) $product_package_list_id;

        $this->load->model('package_product_list_model');
        $res = $this->package_product_list_model->del($product_package_list_id);

        if($res)
        {
            $this->_response_json([
                'status' => 1,
                'message' => 'Member berhasil dihapus'
            ]);
        }
        else
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Member gagal dihapus'
            ]);
        }
    }
}
