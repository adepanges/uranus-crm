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
            'tax_number' => $this->input->post('tax_number'),
            'address' => $this->input->post('address'),
            'status' => (int) $this->input->post('status')
        ];

        if(isset($_FILES['logo']))
        {
            $file_name = $this->do_upload('logo');
            if(!empty($file_name))
            {
                $data['logo'] = $file_name;
            }
        }
        
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

    function do_upload($field_name)
    {
        $config['upload_path'] = FCPATH.'public/images/logo/franchise/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['file_ext_tolower'] = TRUE;
        $config['overwrite'] = TRUE;

        $config['file_name'] = 'logo_'.$this->normaliza_name($this->input->post('code')).'_'.$this->normaliza_name($this->input->post('name'));

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload($field_name))
        {
            dd($this->upload);
            exit;

            $this->_response_json([
                'status' => 0,
                'message' => strip_tags($this->upload->display_errors())
            ]);
        }
        else
        {
            return $this->upload->data('file_name');
        }
    }
}
