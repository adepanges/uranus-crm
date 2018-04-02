<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Postback extends Management_Controller {

	public function index($id = 0)
	{
        $this->_restrict_access('management_network_postback');
        $id = (int) $id;

        $this->load->model(['network_model','master_model']);
        $network = $this->network_model->get_byid($id);
        $event = $this->master_model->event()->result();

        if(empty($network)) redirect('network');

        $this->_set_data([
            'title' => 'Postback - Network',
            'network' => $network,
            'event' => $event
        ]);

        $this->blade->view('inc/management/network/postback', $this->data);
	}

    function get($id = 0)
    {
        $this->_restrict_access('management_network_postback_list', 'rest');
        $id = (int) $id;

        $this->load->model('network_postback_model');
        $this->network_postback_model->set_datatable_param($this->_datatable_param());
        $member_data = $this->network_postback_model->get_datatable($id);

        $this->_response_json([
            'recordsFiltered' => $member_data['total'],
            'data' => $member_data['row']
        ]);
    }

    public function get_byid($id = 0)
    {
        $this->_restrict_access('management_network_postback_list', 'rest');
        $data = (object) [];
        $id = (int) $id;

        if($id)
        {
            $this->load->model('network_postback_model');
            $data = $this->network_postback_model->get_byid($id);
        }
        $this->_response_json($data);
    }

    public function save()
    {
        $network_postback_id = (int) $this->input->post('network_postback_id');
        if($network_postback_id) $this->_restrict_access('management_network_postback_add', 'rest');
        else $this->_restrict_access('management_network_postback_upd', 'rest');

        $data = [
            'network_id' => (int) $this->input->post('network_id'),
            'event_id' => (int) $this->input->post('event_id'),
            'link' => $this->input->post('link'),
            'status' => (int) $this->input->post('status')
        ];

        $this->load->model('network_postback_model');
        if(!$network_postback_id)
        {
            // tambah
            $res = $this->network_postback_model->add($data);
        }
        else
        {
            // ubah
            $res = $this->network_postback_model->upd($data, $network_postback_id);
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

    function del($network_postback_id = 0)
    {
        // $this->_restrict_access('management_cs_team_member_del', 'rest');
        $network_postback_id = (int) $network_postback_id;

        $this->load->model('network_postback_model');
        $res = $this->network_postback_model->del($network_postback_id);

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
