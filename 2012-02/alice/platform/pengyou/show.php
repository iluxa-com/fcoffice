<?php
require_once '../../config.php';

// 获取默认好友
$defaultFriendInfoArr = App::get('DefaultFriend', false);
$defaultSnsUid = NULL;
if ($defaultFriendInfoArr !== false) {
    $defaultSnsUid = $defaultFriendInfoArr['sns_uid'];
}
$friendModel = new FriendModel('*');
$userArr = array();
$keyArr = array();
$num = 0;
$dbSize = $friendModel->RH()->dbSize();
while ($dbSize > 5 && count($userArr) < 10 && ++$num < 100) {
    $key = $friendModel->RH()->randomKey();
    if (in_array($key, $keyArr)) { // 已经在列表里面,跳过
        continue;
    }
    $keyArr[] = $key;
    $dataStr = $friendModel->RH()->get($key);
    if ($dataStr === false) { // 没获取到,跳过
        continue;
    }
    $dataStr = User::uncompress($dataStr);
    $dataArr = json_decode($dataStr, true);
    if ($dataArr === NULL) { // 解码失败,跳过
        continue;
    }
    $tempArr = $dataArr[array_rand($dataArr)];
    if ($tempArr['sns_uid'] != $defaultSnsUid) { // 不把默认好友放进去
        $userArr[$tempArr['sns_uid']] = $tempArr;
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>玩家展示</title>
<script type="text/javascript" src="http://qzonestyle.gtimg.cn/ac/applib/fusion/lib_v1.5.js" charset="utf-8"></script>
<script type="text/javascript">
window.onload = function() {
	fusion.init({"platform": "pengyou"});
	setTimeout(function(){document.getElementById('refreshLink').style.visibility='visible';}, 1500);
}
</script>
<style type="text/css">
* {
	margin:0;
	padding:0;
}
ol, ul, li {
	list-style:none;
}
img {
	border:none;
}
a {
	color:#077D01;
	text-decoration:none;
}
body {
	font-size:12px;
	line-height:100%;
}
.main {
	width:760px;
    margin:0 auto;
	overflow:hidden;
}
.main .title {
	margin:2px 0;
}
.main li {
	float:left;
	width:76px;
	text-align:center;
}
.main li .head {
	padding:4px 0;
}
.main li .head img {
	width:72px;
	height:72px;
	margin:0 auto;
}
</style>
</head>
<body>
<div class="main">
  <div class="title">他们正在玩《迷城历险记》 <a id="refreshLink" style="visibility:hidden" href="javascript:self.location.reload();">刷新</a></div>
  <ul>
    <?php
foreach ($userArr as $user) {
    $user['head_img'] = preg_replace('/\/60$/', '/100', $user['head_img']);
    echo <<<EOT
    <li>
      <div class="head"><a href="javascript:fusion.nav.toFriendHome('{$user['sns_uid']}');"><img src="{$user['head_img']}" /></a></div>
      <div class="name"><a href="javascript:fusion.nav.toFriendHome('{$user['sns_uid']}');">{$user['username']}</a></div>
    </li>
EOT;
}
?>
  </ul>
</div>
</body>
</html>
