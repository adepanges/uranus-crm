<?php

define('ENVIRONMENT', 'development');
define('INDEX_PAGE', pathinfo(__FILE__, PATHINFO_BASENAME));

$system_path = '../codeigniter/system_3.1.7';
$application_folder = '../module/portal';

require_once('../launch.php');
