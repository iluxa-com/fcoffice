<?php
$configArr = array(
    array(
        'name' => '童话迷城-Alice(新版)',
        'register' => 'http://alice-dev.fanhougame.com/%s/platform/devel/register.php',
        'login' => 'http://alice-dev.fanhougame.com/%s/platform/devel/login.php',
        'admin' => 'http://alice-dev.fanhougame.com/%s/admin/',
        'account' => 'http://alice-dev.fanhougame.com/%s/platform/devel/account.php',
        'gateway' => 'http://alice-dev.fanhougame.com/%s/gateway.php',
        'version' => array(
            'alice-new-devel-php' => 'PHP开发',
            'alice-new-devel-as' => 'AS开发',
            'alice-new-test-one' => '测试(一服)',
            'alice-new-test-two' => '测试(二服)',
            'alice-new-ga' => '本地GA',
        ),
    ),
    array(
        'name' => '童话迷城-Alice(旧版)',
        'register' => 'http://alice-dev.fanhougame.com/%s/platform/devel/register.php',
        'login' => 'http://alice-dev.fanhougame.com/%s/platform/devel/login.php',
        'admin' => 'http://alice-dev.fanhougame.com/%s/admin/',
        'account' => 'http://alice-dev.fanhougame.com/%s/platform/devel/account.php',
        'gateway' => 'http://alice-dev.fanhougame.com/%s/gateway.php',
        'version' => array(
            'alice-devel-php' => 'PHP开发',
            'alice-devel-as' => 'AS开发',
            'alice-test' => '测试',
            'alice-ga' => '本地GA',
        ),
    ),
);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>饭后网址导航</title>
<style type="text/css">
* {
	padding:0;
	margin:0;
}
ul, li {
	list-style-type:none;
}
a {
	color:#00f;
	text-decoration:none;
}
body {
	width:1024px;
	margin:0 auto;
	font-size:13px;
}
table {
	width:100%;
	margin:0 auto;
	background:#ddd;
	text-align:center;
	margin-top:6px;
}
table th, table td {
	background:#fff;
}
table tr {
	height:26px;
	line-height:26px;
}
table .td1 {
	width:104px;
}
table .td2 {
	width:104px;
}
table .td3 {
	width:104px;
}
table .td4 {
	width:104px;
}
table .td5 {
	width:104px;
}
table .td6 {
}
#footer {
	text-align:center;
	margin-top:6px;
}
.hotList {
	width:100%;
	margin-top:6px;
}
.hotList li {
	float:left;
	width:100px;
	text-align:center;
	border:1px solid #eee;
	height:30px;
	line-height:30px;
	margin:0 3px;
}
.hotList li a {
	display:inline-block;
	width:100%;
}
</style>
</head>
<body>
<?php
foreach ($configArr as $config) {
    echo <<<EOT
<table border="0" cellpadding="0" cellspacing="1">
  <tr>
    <th colspan="6">{$config['name']}</th>
  </tr>
  <tr>
    <th class="td1">使用者</th>
    <th class="td2">注册页面</th>
    <th class="td3">登录页面</th>
    <th class="td4">后台页面</th>
    <th class="td5">测试帐号页面</th>
    <th class="td6">gateway</th>
  </tr>
EOT;
    foreach ($config['version'] as $key => $val) {		
        $registerUrl = sprintf($config['register'], $key);
        $loginUrl = sprintf($config['login'], $key);
        $adminUrl = sprintf($config['admin'], $key);
        $accountUrl = sprintf($config['account'], $key);
        $gatewayUrl = sprintf($config['gateway'], '<b style="color:red;">' . $key . '</b>');
        echo <<<EOT
  <tr>
    <td>{$val}</td>
    <td><a href="{$registerUrl}">点击注册</a></td>
    <td><a href="{$loginUrl}">点击登录</a></td>
    <td><a href="{$adminUrl}">点击进入</a></td>
    <td><a href="{$accountUrl}">点击查看</a></td>
    <td style="text-align:left;text-indent:6px;">{$gatewayUrl}</td>
  </tr>
EOT;
    }
echo <<<EOT
</table>
EOT;
}
?>
<div id="footer">
  <div>说明：非开发或测试人员，请使用“本地GA”稳定版试玩。</div>
  <div>Alice Host：<b>192.168.0.222 alice-dev.fanhougame.com alice-res.fanhougame.com newui.com</b></div>
</div>
<ul class="hotList">
  <li><a href="http://192.168.0.222/diancan/">点餐系统</a></li>
  <li><a href="http://192.168.0.222/salary_calc.html">工资计算器</a></li>
  <li><a href="http://192.168.0.222/bbs/">内部论坛</a></li>
  <li><a href="http://192.168.0.188/">OA系统</a></li>
</ul>
</body>
</html>
