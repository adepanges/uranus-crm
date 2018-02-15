<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dermeva_Controller extends CI_Controller {
    protected $data = [];

    function __construct()
    {
        parent::__construct();
        $this->load->helper('uranus');
    }

    protected function _set_data($data)
    {
        if(!empty($data) && is_array($data))
        {
            $this->data = array_merge($this->data, $data);
        }
    }

    protected function _datatable_param()
    {
        $params = [
            'start' => (int) $this->input->post('start'),
            'length' => (int) $this->input->post('length'),
            'columns' => $this->input->post('columns'),
            'order' => $this->input->post('order'),
            'search' => $this->input->post('search')
        ];
        if(isset($params['columns']) && is_array($params['columns']))
        {
            foreach ($params['columns'] as $key => $value) {
                $params['columns'][$key] = $value['data'];
            }
        }

        if(!$params['length']) $params['length'] = 10;

        $column = 'id';
        $dir = 'asc';
        if(is_array($params['order']) && !empty($params['order']))
        {
            if(
                isset($params['order'][0]['column']) &&
                isset($params['order'][0]['dir'])
                )
            {
                $index = (int) $params['order'][0]['column'];
                $column = $params['columns'][$index];
                $dir = $params['order'][0]['dir'];
            }
        }

        $params['order'] = [
            'column' => $column,
            'dir' => $dir
        ];

        $params['search'] = trim($params['search']['value']);
        return $params;
    }

    protected function _response_json($resp)
    {
        header('Content-Type: application/json');
        echo json_encode($resp);
        exit;
    }
}
