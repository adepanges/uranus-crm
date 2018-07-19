<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Symfony\Component\DomCrawler\Crawler;

class BRI_XLS_0 extends Keuangan_Model {
    protected
        $file = '',
        $parsed_data = [],
        $data = [],
        $column = [],
        $expected_column = [
            "TANGGAL",
            "TRANSAKSI",
            "DEBET",
            "KREDIT",
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
        $handle = fopen($this->file, "r");
        $html = fread($handle, filesize($this->file));

        $crawler = new Crawler($html);

        $nodeHeader = $crawler->filter('#tabel-saldo thead th')->each(function (Crawler $node, $i) {
            $value = $this->normaliza_column_name(trim($node->text()));
            $this->column[$i] = $value;
        });

        $nodeBody = $crawler->filter('#tabel-saldo tbody tr')->each(function (Crawler $node, $i) {
            if($i > 0)
            {
                $data_parsed = [];
                $nodeTr = $node->filter('td')->each(function (Crawler $nodeTd, $iTd) use(&$data_parsed)
                {
                    $value = $this->clean_white_space(trim($nodeTd->text()));
                    if(
                        isset($this->column[$iTd]) &&
                        !empty($value)
                    )
                    {
                        if(!isset($data_parsed[$this->column[$iTd]]))
                        {
                            $data_parsed[$this->column[$iTd]] = $value;
                        }
                        else
                        {
                            $data_parsed[$this->column[$iTd]] .= ', '.$value;
                        }
                    }
                    else
                    {
                        $data_parsed[$this->column[$iTd]] = '';
                    }

                });

                $this->data[] = $data_parsed;
            }
        });

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
        $count = count($this->data);

        foreach ($this->data as $key => $value) {
            $tmp_parsed = $this->expected_data;

            if ($key >= ($count - 2)) {
                continue;
            }

            $cr = 0;
            $db = 0;
            $note = [];

            if(isset($value['KREDIT'])) $cr = (double) str_replace('.','', $value['KREDIT']);
            if(isset($value['DEBET'])) $db = (double) str_replace('.','', $value['DEBET']);

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

            if(isset($value['TRANSAKSI'])) $note[] = 'INFO: '.$value['TRANSAKSI'];

            $tmp_parsed['note'] = implode(', ', $note);
            $time = date_parse_from_format('d/m/y',$value['TANGGAL']);

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
