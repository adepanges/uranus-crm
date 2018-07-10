<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_franchise_model extends Pengaturan_Model {

    function get_setting($franchise_id = 1)
    {
        $setting = [];
        $sql = "SELECT
        	b.setting_franchise_id, a.name,
        	CASE WHEN b.value IS NULL THEN a.default
        	ELSE b.`value`
        	END AS `value`
        FROM setting_point a
        LEFT JOIN setting_franchise b ON a.setting_point_id = b.setting_point_id AND b.franchise_id = ?";
        $query = $this->db->query($sql, [$franchise_id]);

        foreach ($query->result() as $key => $value) {
            $setting[$value->name] = $value->value;
        }
        return $setting;
    }

    function add($franchise_id, $key, $value)
    {
        return $this->db->query("INSERT INTO setting_franchise
            (franchise_id, setting_point_id, name, `value`)
        SELECT ?, a.setting_point_id, a.name, ?
        FROM setting_point a
        LEFT JOIN setting_franchise b on a.setting_point_id = b.setting_point_id and b.franchise_id = ?
        WHERE
            a.name = ? AND
            b.setting_franchise_id IS NULL LIMIT 1", [
            $franchise_id, (int) $value, $franchise_id, $key
        ]);
    }

    function clear($franchise_id)
    {
        return $this->db->delete('setting_franchise',[
            'franchise_id' => $franchise_id
        ]);
    }

    function get_setting_point()
    {
        return $this->db->get('setting_point')->result();
    }
}
