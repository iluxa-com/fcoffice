<?php
/**
 * 生成随机Key
 * @param int $length
 * @return string
 */
function genRandKey($length) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $maxIndex = strlen($chars) - 1;
    $key = '';
    for ($i = 0; $i < $length; ++$i) {
        $key .= $chars[rand(0, $maxIndex)];
    }
    return $key;
}
require_once '../config.php';
if (in_array(App::get('Platform'), array('Devel', 'Local'))) {
    header('Location: index.php');
    exit;
} else {
    $aclArr = isset($_SESSION['ALICE_ADMIN_ACL']) ? $_SESSION['ALICE_ADMIN_ACL'] : array();
    if (!empty($aclArr)) {
        header('Location: index.php');
        exit;
    }
}
if (isset($_GET['do']) && $_GET['do'] === 'login') {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $key = isset($_SESSION['RAND_KEY']) ? $_SESSION['RAND_KEY'] : '';
    $adminUserSQLModel = new AdminUserSQLModel();
    $whereArr = array(
        'email' => $email,
    );
    $dataArr = $adminUserSQLModel->SH()->find($whereArr)->getOne();
    if (!empty($dataArr)) {
        $password2 = $dataArr['password'];
        if ($password == md5($key . $password2)) {
            unset($_SESSION['RAND_KEY']);
            $loginTime = time();
            $_SESSION['ALICE_ADMIN_ACL'] = array(
                'email' => $email,
                'roles' => $dataArr['roles'],
                'login_time' => $loginTime,
            );
            $dataArr = array(
                'last_login' => $loginTime,
                'last_ip' => App::getIP(),
                '+' => array(
                    'login_times' => 1,
                ),
            );
            $adminUserSQLModel->SH()->find($whereArr)->update($dataArr);
            header('Location: index.php');
            exit;
        } else {
            $msg = 'wrong password';
        }
    } else {
        $msg = 'user not exists';
    }
    exit("Login fail({$msg})!");
}
$key = genRandKey(32);
$_SESSION['RAND_KEY'] = $key;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台管理系统 - Alice</title>
<script type="text/javascript" src="js/md5.js"></script>
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
<script type="text/javascript">
function $(s) {
	return document.getElementById(s);
}
function doSubmit() {
	var k = $('key');
	var e = $('email');
	var p = $('password');
	if (e.value == '') {
		alert('E-mail不能为空！');
		e.focus();
		return false;
    } else if (p.value == '') {
		alert('密码不能为空！');
		p.focus();
		return false;
	}
	p.value = MD5(k.value + MD5(p.value));
	return true;
}
window.onload = function() {
    $('email').focus();
}
</script>
</head>
<body>
<fieldset>
<form action="login.php?do=login" method="post" onsubmit="return doSubmit();">
  <input type="hidden" id="key" name="key" value="<?php echo $key;?>" />
  <table border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td class="td1"><label for="email">E-mail：</label></td>
      <td class="td2"><input type="text" id="email" name="email" /></td>
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
