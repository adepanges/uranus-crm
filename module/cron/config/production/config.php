<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(FCPATH.'configuration/production/config.php');
$config['subclass_prefix'] = 'Cron_';

$config['log_threshold'] = 1;
$config['log_path'] = 'application/logs/cron';
