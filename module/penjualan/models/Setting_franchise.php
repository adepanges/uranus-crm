<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_franchise extends Penjualan_Model {

    function get($franchise_id, $key)
    {
        $sql = "SELECT
                b.franchise_id, a.name,
                CASE
                    WHEN b.value IS NULL THEN a.default
                    ELSE b.`value`
                END AS `value`
            from setting_point a
            LEFT JOIN setting_franchise b on a.setting_point_id = b.setting_point_id and b.franchise_id = ?
            WHERE a.name = ?";
        $data = $this->db->query($sql, [$franchise_id, $key])->first_row();
        return (int) (!empty($data) && isset($data->value))?$data->value:0;
    }
}
