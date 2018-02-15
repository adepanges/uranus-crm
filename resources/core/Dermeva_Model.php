<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dermeva_Model extends CI_Model {
    protected
        $datatable_param = NULL,
        $table = '',
        $orderable_field = [],
        $searchable_field = [],
        $fillable_field = [];

    function __construct()
    {
        parent::__construct();
    }

    function set_datatable_param($params)
    {
        $this->datatable_param = $params;
    }

    protected function _sanity_field($data, $field = [])
    {
        $data_clear = [];
        if(empty($field)) $field = $this->fillable_field;
        if(!empty($field) && !empty($data))
        {
            foreach ($data as $key => $value)
            {
                if(in_array($key, $field))
                {
                    if(is_array($value)) $value = json_encode($data[$value]);
                    $data_clear[$key] = $value;
                }
            }
        }
        return $data_clear;
    }

    protected function _combine_datatable_param($sql, $count = false)
    {
        if(!is_null($this->datatable_param))
        {
            $order_query = "";
            $search_query = [];
            $keyword = "";
            $start = 0;
            $limit = 10;

            if(isset($this->datatable_param['start']))
            {
                $start = (int) $this->datatable_param['start'];
            }

            if(isset($this->datatable_param['length']))
            {
                $limit = (int) $this->datatable_param['length'];
            }

            if(isset($this->datatable_param['search']))
            {
                $keyword = $this->db->escape_str($this->datatable_param['search']);
            }

            if(
                isset($this->datatable_param['order']) &&
                !empty($this->datatable_param['order']))
            {
                if(
                    in_array($this->datatable_param['order']['column'], $this->orderable_field) &&
                    isset($this->datatable_param['order']['dir']))
                {
                    if($this->datatable_param['order']['dir'] != 'asc') $this->datatable_param['order']['dir'] = 'desc';
                    $order_query = "ORDER BY {$this->datatable_param['order']['column']} {$this->datatable_param['order']['dir']}";
                }
            }

            if(isset($this->searchable_field) && !empty($keyword))
            {
                foreach ($this->searchable_field as $key => $value)
                {
                    $search_query[] = "a.{$value} LIKE '%{$keyword}%'";
                }
            }

            if(!empty($search_query)) $search_query = 'WHERE '.implode(' OR ', $search_query);
            else $search_query = '';

            $sql =  "SELECT * FROM ({$sql}) a ".$search_query.' '.$order_query;
            if(!$count)
            {
                $sql .= " LIMIT {$start}, {$limit}";
            }
            else
            {
                $sql =  "SELECT count(*) as count FROM ({$sql}) a ".$search_query.' '.$order_query;
            }
        }
        return $sql;
    }
}
