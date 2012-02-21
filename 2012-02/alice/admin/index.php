<?php
require_once '../config.php';
$platform = App::get('Platform');
if (in_array($platform, array('Devel', 'Local'))) {
    $email = 'public@fanhougame.com';
    $roles = 'user';
} else {
    $aclArr = isset($_SESSION['ALICE_ADMIN_ACL']) ? $_SESSION['ALICE_ADMIN_ACL'] : array();
    if (empty($aclArr)) {
        header('Location: login.php');
        exit;
    }
    $email = $aclArr['email'];
    $roles = $aclArr['roles'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台管理系统 - Alice</title>
<link type="text/css" rel="stylesheet" href="css/global.css" />
<script type="text/javascript" src="js/jquery-1.5.min.js"></script>
<script type="text/javascript" src="js/global.js"></script>
</head>
<body>
<div id="header"><b><?php echo $email; ?></b>&nbsp;您好，欢迎登录后台管理系统！当前所在平台：[<b><?php echo $platform; ?></b>]，登录帐号角色为：[<b><?php echo $roles; ?></b>]。</div>
<div id="main">
  <div id="sideBar">
    <dl>
      <dt>数据统计图表<b>－</b></dt>
      <dd><a href="../gateway.php?service=StatService&action=showSiteStat" ajaxTarget="#ajaxContainer">站点统计</a></dd>
      <dd><a href="../gateway.php?service=StatService&action=showDetailStat" ajaxTarget="#ajaxContainer">详细数据</a></dd>
      <dd><a href="../gateway.php?service=StatService&action=showUserStat" ajaxTarget="#ajaxContainer">用户统计</a></dd>
      <dd><a href="../gateway.php?service=StatService&action=showGradeStat" ajaxTarget="#ajaxContainer">等级分布</a></dd>
      <dd><a href="../gateway.php?service=StatService&action=showStepStat" ajaxTarget="#ajaxContainer">新手流失</a></dd>
      <dd><a href="../gateway.php?service=StatService&action=showLevelStat" ajaxTarget="#ajaxContainer">闯关统计</a></dd>
      <dd><a href="../gateway.php?service=StatService&action=showOtherStat" ajaxTarget="#ajaxContainer">其他数据</a></dd>
    </dl>
    <dl>
      <dt>道具数据管理<b>＋</b></dt>
      <!--<dd><a href="../gateway.php?service=ItemDataService&action=showAdd" ajaxTarget="#ajaxContainer">添加道具</a></dd>-->
      <dd><a href="../gateway.php?service=ItemDataService&action=showList" ajaxTarget="#ajaxContainer">道具列表</a></dd>
      <dd><a href="../gateway.php?service=ItemDataService&action=showSync" ajaxTarget="#ajaxContainer">数据同步</a></dd>
      <dd><a href="../gateway.php?service=ItemDataService&action=showSearch" ajaxTarget="#ajaxContainer">道具ID查询</a></dd>
    </dl>
    <dl>
      <dt>任务数据管理<b>＋</b></dt>
      <!--<dd><a href="../gateway.php?service=TaskDataService&action=showAdd" ajaxTarget="#ajaxContainer">添加任务</a></dd>-->
      <dd><a href="../gateway.php?service=TaskDataService&action=showList" ajaxTarget="#ajaxContainer">任务列表</a></dd>
      <dd><a href="../gateway.php?service=TaskDataService&action=showSync" ajaxTarget="#ajaxContainer">数据同步</a></dd>
      <dd><a href="../gateway.php?service=TaskDataService&action=showExport" ajaxTarget="#ajaxContainer">导出数据</a></dd>
    </dl>
    <dl>
      <dt>区域数据管理<b>－</b></dt>
      <dd><a href="../gateway.php?service=ZoneDataService&action=showAdd" ajaxTarget="#ajaxContainer">添加区域</a></dd>
      <dd><a href="../gateway.php?service=ZoneDataService&action=showList" ajaxTarget="#ajaxContainer">区域列表</a></dd>
    </dl>
    <dl>
      <dt>地点数据管理<b>－</b></dt>
      <dd><a href="../gateway.php?service=PlaceDataService&action=showAdd" ajaxTarget="#ajaxContainer">添加地点</a></dd>
      <dd><a href="../gateway.php?service=PlaceDataService&action=showList" ajaxTarget="#ajaxContainer">地点列表</a></dd>
    </dl>
    <dl>
      <dt>地区数据管理<b>＋</b></dt>
      <dd><a href="../gateway.php?service=AreaDataService&action=showAdd" ajaxTarget="#ajaxContainer">添加地区</a></dd>
      <dd><a href="../gateway.php?service=AreaDataService&action=showList" ajaxTarget="#ajaxContainer">地区列表</a></dd>
    </dl>
    <dl>
      <dt>用户数据管理<b>＋</b></dt>
      <dd><a href="../gateway.php?service=UserDataService&action=showIdConvert" ajaxTarget="#ajaxContainer">ＩＤ转换(<span>*</span>)</a></dd>
      <dd><a href="../gateway.php?service=UserDataService&action=showQuery" ajaxTarget="#ajaxContainer">信息查询(<span>*</span>)</a></dd>
      <dd><a href="../gateway.php?service=UserDataService&action=showDataModify" ajaxTarget="#ajaxContainer">数据修改(<span>*</span>)</a></dd>
      <dd><a href="../gateway.php?service=UserDataService&action=showAccountDebug" ajaxTarget="#ajaxContainer">帐号调试(<span>*</span>)</a></dd>
    </dl>
    <dl>
      <dt>后台用户管理<b>－</b></dt>
      <dd><a href="../gateway.php?service=AdminUserService&action=showAdd" ajaxTarget="#ajaxContainer">添加用户</a></dd>
      <dd><a href="../gateway.php?service=AdminUserService&action=showList" ajaxTarget="#ajaxContainer">用户列表</a></dd>
    </dl>
    <dl>
      <dt>辅助管理工具<b>＋</b></dt>
      <dd><a href="../tools/redis-cli.php" target="_blank">Redis Web Client</a></dd>
      <dd><a href="../tools/memcache.php" target="_blank">Memcache</a></dd>
      <dd><a href="../tools/poster.php" target="_blank">Poster</a></dd>
      <dd><a href="../tools/post_data_decoder.php" target="_blank">Post Data Decoder</a></dd>
      <dd><a href="../tools/time_converter.php" target="_blank">Time Converter</a></dd>
    </dl>
    <dl>
      <dt>系统相关链接<b>＋</b></dt>
      <dd><a class="cur" href="../gateway.php?service=AdminService&action=showDebugLog" ajaxTarget="#ajaxContainer">调试日志</a></dd>
      <dd><a href="../gateway.php?service=AdminService&action=showEnv" ajaxTarget="#ajaxContainer">环境切换</a></dd>
      <dd><a href="../gateway.php?service=AdminService&action=showResourcePublish" ajaxTarget="#ajaxContainer">资源发布</a></dd>
      <dd><a href="../gateway.php?service=AdminService&action=showShortcut" ajaxTarget="#ajaxContainer">快捷方式</a></dd>
      <dd><a href="logout.php">注销登录</a></dd>
    </dl>
  </div>
  <div id="ajaxContainer">loading...</div>
</div>
<script type="text/javascript">
$(function(){
	$('#sideBar').css({'min-height': ($(window).height() - $('#header').outerHeight()) + 'px'});
	$('#sideBar dt').each(function(){
		$(this).click(function() {
			var b = $(this).find('b');
			if (b.html() == '＋') {
				b.html('－');
				$(this).closest('dl').find('dd').show();
			} else {
				b.html('＋');
				$(this).closest('dl').find('dd').hide();
			}
		});
		$(this).trigger('click');
	});
	var obj = $('#sideBar a');
	obj.click(function(){
		obj.removeClass('hover');
		$(this).addClass('hover');
	});
	obj.filter('.cur').trigger('click');
});
</script>
<script type="text/javascript" src="js/swfobject.min.js"></script>
<script type="text/javascript" src="js/jquery.json-2.2.min.js"></script>
<link type="text/css" rel="stylesheet" href="css/redmond/jquery-ui-1.8.9.custom.css" />
<script type="text/javascript" src="js/jquery-ui-1.8.9.custom.min.js"></script>
</body>
</html>
