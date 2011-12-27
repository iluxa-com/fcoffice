<?php
//echo strtotime('18:30');
// touch('test_ok');
// die();
require_once('PHPFetion.php');
$tel = '13714681456';
$fetion_psw = '7340985cxm';
$msg = "test me";
$fetion = new PHPFetion($tel,$fetion_psw);
$send_res = $fetion->toMyself($msg);
var_dump($send_res);