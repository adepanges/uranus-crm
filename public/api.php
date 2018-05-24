<?php

define('ENVIRONMENT', 'production');
define('INDEX_PAGE', pathinfo(__FILE__, PATHINFO_BASENAME));

$system_path = '../codeigniter/system_3.1.8';
$application_folder = '../module/api';

header("Access-Control-Allow-Origin: *");
require_once('../launch.php');
