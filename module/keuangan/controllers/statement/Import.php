<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Import extends Keuangan_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->driver('cache', array(
            'adapter' => 'file',
            'key_prefix' => 'finance_import_'.$this->franchise->franchise_id.'_'.$this->profile['user_id'].'_',
        ));
    }

    function process($payment_method_id, $key_cache)
    {
        $data = $this->cache->get($key_cache);
        if($data === FALSE) redirect('statement');

        $this->cache->delete($key_cache);

        $this->_restrict_access('account_statement');

        $this->load->model(['payment_method_model']);

        $this->_set_data([
            'title' => 'Process Import Data',
            'account' => $this->payment_method_model->get_active()->result(),
            'data_import' => $data,
            'payment_method_id' => $payment_method_id
        ]);

        $this->blade->view('inc/keuangan/import/process', $this->data);
    }

    function index()
    {
        $params = [
            'payment_method_id' => (int) $this->input->post('payment_method_id'),
            'file' => isset($_FILES['file'])?$_FILES['file']:[]
        ];

        // ini penting untuk mengecek apakah model parsingan tersedia
        $this->_init_parser($params);

        $parsing = $this->parser->parse();
        if($parsing === FALSE)
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Gagal parsing data'
            ]);
        }

        $data = $this->parser->get_parsed_data();

        $key_cache = md5(base64_encode(json_encode($params).json_encode($this->profile).time()));
        $this->cache->save($key_cache, $data, 3600);

        $this->_response_json([
            'status' => 1,
            'message' => 'File berhasil terbaca, silahkan proses lebih lanjut',
            'data' => [
                'key_cache' => $key_cache
            ]
        ]);
    }

    protected function _init_parser($params = [])
    {
        // params needed [payment_method_id, version]
        // return key model name
        $key_model_name = '';

        // jika ingin menambah bank harus menambah jg pada array ini
        $bank_available = [
            '2' => 'BCA',
            '3' => 'BRI',
            '4' => 'MANDIRI'
        ];

        // jika ada tambahan versi parsingan, silahkan tambah pada array dibawah
        $active_parse_version = [
            'MANDIRI' => 0,
            'BRI' => 0,
            'BCA' => 0
        ];
        // jenis file parsingan yg tersedia
        $active_parse_extension = [
            'MANDIRI' => ['csv'],
            'BRI' => ['xls'],
            'BCA' => ['csv']
        ];

        if(isset($bank_available[$params['payment_method_id']]) && isset($params['file']))
        {
            $bank_code = $bank_available[$params['payment_method_id']];
            $active_parse_version_bank = $active_parse_version[$bank_code];
            $active_parse_extension_bank = $active_parse_extension[$bank_code];

            $path = $params['file']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            if(!in_array($ext, $active_parse_extension_bank))
            {
                $this->_response_json([
                    'status' => 0,
                    'message' => 'File tidak dapat kami proses'
                ]);
            }
            $key_model_name = strtoupper("{$bank_code}_{$ext}_{$active_parse_version_bank}");
        }
        else
        {
            $this->_response_json([
                'status' => 0,
                'message' => 'Bank tidak tersedia, mohon input data yg sesuai'
            ]);
        }

        if(
            file_exists(APPPATH."models/parser/{$key_model_name}.php")
        )
        {
            if(
                !(
                    !empty($params['file']) &&
                    (
                        isset($params['file']['tmp_name']) &&
                        file_exists($params['file']['tmp_name'])
                    )
                )
            )
            {
                $this->_response_json([
                    'status' => 0,
                    'message' => 'File rusak atau tidak ditemukan'
                ]);
            }

            $this->load->model('parser/'.$key_model_name, 'parser');
            $this->parser->init_file($params['file']['tmp_name']);
        }
        else{
            $this->_response_json([
                'status' => 0,
                'message' => 'Parser tidak ditemukan'
            ]);
        }
        return $key_model_name;
    }
}
