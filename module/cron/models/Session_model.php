<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Session_model extends Cron_Model {

    function clear_web()
    {
        return $this->db->empty_table('sso_session_web');
    }
}
