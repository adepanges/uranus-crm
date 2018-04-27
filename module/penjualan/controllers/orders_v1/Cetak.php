<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Cetak extends Penjualan_Controller {

    public function invoice($id = '')
    {
        if(empty($id)) redirect();
        $id = (int) $id;

        $this->load->model(['invoice_model','orders_model']);
        $res = $this->invoice_model->get_invoice($id);

        $data = $res->first_row();

        if(isset($data->customer)) $data->customer = json_decode($data->customer);
        if(isset($data->customer_address)) $data->customer_address = json_decode($data->customer_address);
        if(isset($data->order_cart)) $data->order_cart = $this->orders_model->parse_cart_v1(json_decode($data->order_cart));

        $this->blade->view('cetak/invoice', [
            'invoice' => $data
        ]);
    }

    public function excel($id = '')
    {
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $template_path = FCPATH.'resources/template/product_sale.xlsx';

        if(!file_exists($template_path))
        {
            echo "Template is missing";
            exit;
        }

        $this->load->model('orders_model');
        $id = explode(',', base64_decode($id));
        $res = $this->orders_model->get_byid_bulk($id);

        $styleArray = [
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ]
            ]
        ];

        $spreadsheet = $reader->load($template_path);
        $sheet = $spreadsheet->getActiveSheet();
        $last_row = $start_row = 5;
        $no = 1;

        $field = [
            [
                'field' => 'B',
                'data' => 'order_code'
            ],
            [
                'field' => 'C',
                'data' => 'package_name'
            ],
            [
                'field' => 'D',
                'data' => 'customer_info_fullname'
            ],
            [
                'field' => 'E',
                'data' => 'customer_info_telephone'
            ],
            [
                'field' => 'F',
                'data' => 'customer_address_join'
            ],
            [
                'field' => 'G',
                'data' => 'total_product'
            ],
            [
                'field' => 'H',
                'data' => 'total_price'
            ],
            [
                'field' => 'I',
                'data' => 'logistic_name'
            ]
        ];

        foreach ($res->result() as $key => $value) {
            $value->customer_info = json_decode($value->customer_info);
            $value->customer_address = json_decode($value->customer_address);

            $value->customer_info_fullname = $value->customer_info->full_name;
            $value->customer_info_telephone = $value->customer_info->telephone;
            $value->customer_address_join = "{$value->customer_address->address} Des./Kel. {$value->customer_address->desa_kelurahan} Kec. {$value->customer_address->kecamatan} Kab. {$value->customer_address->kabupaten} Prov. {$value->customer_address->provinsi} {$value->customer_address->postal_code}";

            unset($value->customer_info);
            unset($value->customer_address);

            $sheet->setCellValue('A'.$last_row, $no);
            $spreadsheet->getActiveSheet()->getStyle('A'.$last_row)->applyFromArray($styleArray);
            foreach ($field as $key => $value_field) {
                $value_field = (object) $value_field;

                $cell = $value_field->field.$last_row;
                $value_cell = $value->{$value_field->data};

                $sheet->setCellValue($cell,$value_cell);
                $spreadsheet->getActiveSheet()->getStyle($cell)->applyFromArray($styleArray);
                if($value_field->data == 'total_price')
                {
                    $spreadsheet->getActiveSheet()->getStyle($cell)->getNumberFormat()->
                    setFormatCode('_-Rp* #.##0_-;-Rp* #.##0_-;_-Rp* "-"_-;_-@_-');
                }
            }
            $last_row++;
            $no++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="orders_sale_'.md5(time()).'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}
