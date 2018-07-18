<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BCA_CSV_0 extends Keuangan_Model {
    protected
        $file = '',
        $parsed_data = [],
        $data = [],
        $column = [],
        $expected_column = [
            "TANGGAL_TRANSAKSI",
            "KETERANGAN",
            "CABANG",
            "JUMLAH",
            "SALDO",
        ],
        $expected_data = [
            'parent_statement_id' => 0,
            'transaction_type' => '',
            'transaction_date' => '',
            'transaction_amount' => '',
            'balance' => '',
            'note' => '',
            'is_sales' => 0
        ];

    function init_file($file)
    {
        $this->file = $file;
    }

    function get_parsed_data()
    {
        return $this->parsed_data;
    }

    function parse()
    {
        $row = 0;
        if (($handle = fopen($this->file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
                $row++;
                $data_parsed = [];

                if(!empty($data))
                {
                    if($row == 7)
                    {
                        foreach ($data as $key => $value)
                        {
                            $value = $this->normaliza_column_name(trim($value));
                            if(!empty($value)) $this->column[$key] = $value;
                        }
                    }

                    foreach ($data as $key => $value)
                    {
                        $value = $this->clean_white_space(trim($value));
                        if(
                            isset($this->column[$key]) &&
                            !empty($value)
                        )
                        {
                            if(!isset($data_parsed[$this->column[$key]]))
                            {
                                $data_parsed[$this->column[$key]] = $value;
                            }
                            else
                            {
                                $data_parsed[$this->column[$key]] .= ', '.$value;
                            }
                        }
                    }

                }

                if($row > 7)
                {
                    $this->data[] = $data_parsed;
                }
            }
            fclose($handle);
        }

        foreach ($this->expected_column as $key => $value)
        {
            if(!in_array($value, $this->column))
            {
                return FALSE;
            }
        }


        $this->_process_parsed_data();
    }

    private function _process_parsed_data()
    {
        // dd($this->expected_field);
        $check_validation = FALSE;

        foreach ($this->data as $key => $value) {
            $tmp_parsed = $this->expected_data;

            $cr = 0;
            $db = 0;
            $note = [];

            if(isset($value['JUMLAH']))
            {
                $amount_type = explode(' ', $value['JUMLAH']);
                if(isset($amount_type[0]))
                {
                    $tmp_parsed['transaction_amount'] = (double) trim(str_replace(',','', $amount_type[0]));
                }

                if(isset($amount_type[1]))
                {
                    $type = trim(strtoupper($amount_type[1]));
                    switch ($type) {
                        case 'CR':
                            $tmp_parsed['transaction_type'] = 'K';
                            $tmp_parsed['is_sales'] = 1;
                            break;

                        case 'DB':
                            $tmp_parsed['transaction_type'] = 'D';
                            break;
                    }
                }

                if(isset($value['KETERANGAN'])) $note[] = 'KET: '.$value['KETERANGAN'];

                $tmp_parsed['note'] = implode(', ', $note);

                $time = date_parse_from_format('d/m/y',$value['TANGGAL_TRANSAKSI']);

                if($time['error_count'] > 0)
                {
                    $time = 0;
                }
                else
                {
                    $time = mktime($time['hour'],$time['minute'],$time['second'],$time['month'],$time['day'],$time['year']);
                }

                $tmp_parsed['transaction_date'] = date('Y-m-d', $time);
                $this->parsed_data[] = $tmp_parsed;
            }
        }
    }
}
