<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends Pengaturan_Controller {

	public function index()
	{
        $this->_restrict_access('pengaturan_franchise', 'web');
        $this->load->model('setting_franchise_model');

        $setting = $this->setting_franchise_model->get_setting($this->franchise->franchise_id);

        $this->_set_data([
            'title' => 'Pengaturan',
            'settings' => $this->setting_franchise_model->get_setting($this->franchise->franchise_id),
            'franchise' => $this->franchise
        ]);

        $this->blade->view('inc/pengaturan/app', $this->data);
	}

    public function save()
    {
        $this->_restrict_access('pengaturan_franchise_save', 'rest');

        $this->load->model('setting_franchise_model');
        $this->setting_franchise_model->clear($this->franchise->franchise_id);

        foreach ($this->setting_franchise_model->get_setting_point() as $key => $value) {
            $value_seting = (int) $this->input->post($value->name);
            $this->setting_franchise_model->add($this->franchise->franchise_id, $value->name, $value_seting);
        }

        $this->_response_json([
            'status' => 1,
            'message' => 'Berhasil menyimpan data'
        ]);
    }
}
