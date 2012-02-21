<?php
$accountArr = array(
    'fh023@fanhougame.com' => '123456',
    'editor001' => 'qbSAKWqJ',
    'editor002' => 'vnIFwmNq',
    'editor003' => 'vhmssSiB',
    'editor004' => '74xjm9HJ',
);
if (isset($_GET['do']) && $_GET['do'] === 'login') {
    $account = isset($_POST['account']) ? $_POST['account'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $movie = isset($_POST['movie']) ? $_POST['movie'] : '';
    if ($movie === '') {
        exit('invalid movie!');
    }
    if (!isset($accountArr[$account])) {
        exit('account not exists!');
    }
    if ($accountArr[$account] != $password) {
        exit('wrong password!');
    }
    session_start();
    $_SESSION['ALICE_EDITOR_ACL'] = array(
        'account' => $account,
        'login_time' => time(),
    );
    $movie = base64_decode($movie);
    // 登录成功,跳转
    header("Location: load.php?movie={$movie}");
    exit;
}
$movie = isset($_GET['movie']) ? $_GET['movie'] : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Alice Editor Login</title>
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
  <input type="hidden" name="movie" value="<?php echo $movie; ?>" />
  <table border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td class="td1"><label for="account">帐号：</label></td>
      <td class="td2"><input type="text" id="email" name="account"  /></td>
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
