<?php
/*
测试QQ号
1725298384
7340985cxm
*/
$app_id = '1725298384';
$app_key = '2ecd3d48b32c123420c325d3511b418a';
$redirect_uri = $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
//var_dump($redirect_uri);
$auth_url = "
https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=$app_id&redirect_uri=$redirect_uri&scope=get_user_info,add_pic_t,add_idol,add_share,add_t";

echo "<a href='{$auth_url}'><img src='Connect_logo_5.png' /></a>";