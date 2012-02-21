<?php
if (php_sapi_name() !== 'cli') {
    exit('This script can only run under cli mode!');
}

$_SERVER['HTTP_HOST'] = '127.0.0.1';
require_once dirname(__FILE__) . '/../config.php';
?>