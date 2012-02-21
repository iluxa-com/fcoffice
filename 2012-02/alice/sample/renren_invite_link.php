<?php
require_once 'config.inc.php';

/**
 * @link http://wiki.dev.renren.com/wiki/Request_dialog#.E9.82.80.E8.AF.B7.E5.A4.9A.E4.BA.BA.28.E5.8F.AA.E6.94.AF.E6.8C.81page.E6.A8.A1.E5.BC.8F.29
 * @link http://widget.renren.com/dialog/request?actiontext=demoapp%e5%a5%bd%e5%8f%8b%e5%88%97%e8%a1%a8&app_msg=test&app_id=126453&redirect_uri=http%3a%2f%2fapps.renren.com%2fdemo_app&accept_url=http%3a%2f%2fapps.renren.com%2fdemo_app&accept_label=%e8%bf%9b%e5%85%a5%e5%ba%94%e7%94%a8
 */
$paramArr = array(
    'app_id' => 146821,
    'redirect_uri' => 'http://apps.renren.com/dreaming_adventures/?from=invite',
    'accept_url' => 'http://apps.renren.com/dreaming_adventures/?from=invite',
    'accept_label' => '进入应用',
    'actiontext' => '邀请好友开通应用',
    'max_friends' => 30,
    'selector_mode' => 'naf',
    'app_msg' => '欢迎您的加入',
);
$tempArr = array();
foreach ($paramArr as $key => $val) {
    $tempArr[] = $key . '=' . urlencode($val);
}

$urlPrefix = 'http://widget.renren.com/dialog/request?';
$url = $urlPrefix . implode('&', $tempArr);

echo '<a href="' . $url . '">' . $url . '</a>';
?>