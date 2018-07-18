<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MANDIRI_CSV_0 extends Keuangan_Model {
    protected
        $file = '',
        $parsed_data = [],
        $data = [],
        $column = [],
        $expected_column = [
            "ACCOUNT_NO",
            "DATE",
            "VAL_DATE",
            "TRANSACTION_CODE",
            "DESCRIPTION",
            "DESCRIPTION",
            "REFERENCE_NO",
            "DEBIT",
            "CREDIT"
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
                    if($row == 1)
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

                if($row > 1)
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

            if(isset($value['CREDIT'])) $cr = (double) str_replace(',','', $value['CREDIT']);
            if(isset($value['DEBIT'])) $db = (double) str_replace(',','', $value['DEBIT']);

            if(!empty($cr))
            {
                $tmp_parsed['transaction_amount'] = $cr;
                $tmp_parsed['transaction_type'] = 'K';
                $tmp_parsed['is_sales'] = 1;
            }
            else if(!empty($db))
            {
                $tmp_parsed['transaction_amount'] = $db;
                $tmp_parsed['transaction_type'] = 'D';
            }

            if(isset($value['ACCOUNT_NO'])) $note[] = 'ACC_NO: '.$value['ACCOUNT_NO'];
            if(isset($value['TRANSACTION_CODE'])) $note[] = 'TRX_CODE: '.$value['TRANSACTION_CODE'];
            if(isset($value['DESCRIPTION'])) $note[] = 'DESC: '.$value['DESCRIPTION'];

            $tmp_parsed['note'] = implode(', ', $note);

            $time = date_parse_from_format('d/m/y',$value['VAL_DATE']);

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
