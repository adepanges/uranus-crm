<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(FCPATH.'resources/core/Dermeva_Model.php');

class Keuangan_Model extends Dermeva_Model {
    function __construct()
    {
        parent::__construct();
    }

    protected function normaliza_column_name($string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        $string = strtoupper(str_replace('-', '_', $string));
        return $string;
    }

    protected function clean_white_space($string)
    {
        return preg_replace("/[[:blank:]]+/", " ", $string);
    }
}
