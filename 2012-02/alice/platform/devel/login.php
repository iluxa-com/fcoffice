<?php
if (isset($_GET['do']) && $_GET['do'] === 'login') {
    // 引入配置文件
    require_once '../../config.php';

    if (App::get('Platform', false) !== 'Devel') {
        exit('This platform is not supported!');
    }

    if (!empty($_POST)) {
        $tempArr = $_POST;
    } else {
        $tempArr = $_GET;
    }

    // SNS登录
    $dataArr = SNS::login($tempArr);

    // 如果登录失败,退出
    if (!$dataArr) {
        exit('sns login fail');
    }

    // 登录成功,跳转
    header('Location: ' . GATEWAY_URL . 'index.php?sns_uid=' . $dataArr['sns_uid'] . '&sns_session_key=' . $dataArr['sns_session_key']);
    exit;
}
$email = isset($_GET['email']) ? $_GET['email'] : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Alice SNS Login</title>
<style type="text/css">
fieldset {
	width:500px;
	margin:0 auto;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
}
table {
	width:400px;
	margin:50px auto;
}
tr {
	height:32px;
	line-height:32px;
}
.td1 {
	width:100px;
	text-align:right;
	font-weight:bold;
}
.td2 {
	width:200px;
	text-align:center;
}
.td2 input {
	width:195px;
	font-weight:bold;
}
.td2 button {
	width:80px;
	line-height:20px;
}
.td3 {
	width:100px;
	text-align:left;
	text-indent:4px;
}
</style>
</head>
<body>
<fieldset>
<form action="login.php?do=login" method="post">
  <table border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td class="td1">&nbsp;</td>
      <td class="td2"><a href="account.php">测试帐号列表</a></td>
      <td class="td3">&nbsp;</td>
    </tr>
    <tr>
      <td class="td1">&nbsp;</td>
      <td class="td2"><a href="register.php">没有账号，前去注册</a></td>
      <td class="td3">&nbsp;</td>
    </tr>
    <tr>
      <td class="td1"><label for="email">E-mail：</label></td>
      <td class="td2"><input type="text" id="email" name="email" value="<?php echo $email;?>" /></td>
      <td class="td3">*</td>
    </tr>
    <tr>
      <td class="td1"><label for="password">密码：</label></td>
      <td class="td2"><input type="password" id="password" name="password" maxlength="32" /></td>
      <td class="td3">*</td>
    </tr>
    <tr>
      <td class="td1">&nbsp</td>
      <td class="td2"><button type="submit">登录</button>&nbsp;<button type="reset">重置</button></td>
      <td class="td3">&nbsp;</td>
    </tr>
  </table>
</form>
</fieldset>
</body>
</html>
