<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>童话迷城</title>
<script type="text/javascript" src="<?php echo RESOURCE_URL; ?>js/swfobject.min.js?v=1.0"></script>
<script type="text/javascript" src="<?php echo RESOURCE_URL; ?>js/global.js?v=1.2"></script>
<script type="text/javascript" src="<?php echo RESOURCE_URL; ?>js/4399.js?v=2.1"></script>
<script type="text/javascript" src="<?php echo RESOURCE_URL; ?>js/jquery-1.5.min.js"></script>
<style type="text/css">
* {
	margin:0;
	padding:0;
}
a {
	color:#00f;
	text-decoration:none;
}
img {
	border:none;
}
body {
	text-align:center;
	font-family:宋体;
	font-size:12px;
	width:800px;
	margin:0 auto;
}
#top {
	background:#97A1A3;
	color:#fff;
	padding:8px 0;
	margin-bottom:6px;
	font-size:13px;
	font-weight:bold;
}
#nav {
	margin-bottom:6px;
}
#main {
	width:800px;
	margin:0 auto;
	overflow:hidden;
}
#footer {
	margin-top:6px;
	padding:10px 0;
	border:1px solid #e9e9e9;
	color:#606060;
	line-height:180%;
}
</style>
</head>
<body>
<?php
if (defined('IS_TEST_SERVER')) {
    echo <<<EOT
<div id="top">《童话迷城》－4399测试环境</div>
EOT;
}
?>
<div id="nav"><img src="<?php echo RESOURCE_URL; ?>images/banner2.jpg" width="800" height="80" border="0" usemap="#Map" /></div>
<map name="Map" id="Map">
  <area shape="poly" coords="196,54,287,38,283,10,194,23" href="javascript:void(0);" onclick="self.location.reload();" alt="开始" />
  <area shape="poly" coords="311,46,405,58,410,29,319,17" href="javascript:void(0);" onclick="FH.inviteFriend();" alt="邀请" />
  <area shape="poly" coords="437,45,536,39,536,7,436,12" href="javascript:void(0);" alt="充值" />
  <area shape="poly" coords="560,49,653,59,656,32,563,20" href="http://my.4399.com/space-mtag-tagid-81173.html" target="_blank" alt="讨论" />
  <area shape="poly" coords="678,51,772,42,770,12,678,22" href="http://my.4399.com/space-361183138-do-thread-id-2224033-tagid-81173.html" target="_blank" alt="帮助" />
</map>
<div id="main">
  <noscript>
  Sorry,your browser does not support or enable JavaScript!
  </noscript>
  <div id="flashDiv">loading...</div>
</div>
<div id="footer">
  <div>《童话迷城》　您的用户ID：<?php echo $userId; ?>　官方玩家交流群：<a href="http://qun.qq.com/#jointhegroup/gid/184231702" target="_blank">184231702</a></div>
  <div><b>声明：此应用由“<a href="http://www.fanhougame.com/?ref=alice_4399" target="_blank">饭后游戏</a>”提供，若您在游戏中遇到问题，可以直接联系“<a href="http://wpa.qq.com/msgrd?v=1&uin=2212844964&site=alice&menu=yes" target="_blank">在线客服</a>”，客服人员将会尽快为您解答。</b></div>
  <div><span style="color:#f00;">健康忠告：</span>抵制不良游戏，拒绝盗版游戏。注意自我保护，谨防受骗上当。适度游戏益脑，沉迷游戏伤身。合理安排时间，享受健康生活。</div>
</div>
<script type="text/javascript">
(function() {
	function log(str) {
		if (typeof(console) != 'undefined' && typeof(console.log) == 'function') {
			console.log(str);
		}
	}
	var swfUrl = '<?php echo RESOURCE_URL; ?>flash/PreLoading-<?php echo LOADING_SIGN; ?>.swf',
	id = 'flashDiv',
	width = '800',
	height = '680',
	version = '9.0.0',
	expressInstallSwfUrl = '',
	flashVars = {
		platform: '<?php echo $platform; ?>',
		gatewayUrl: encodeURIComponent('<?php echo GATEWAY_URL . 'gateway.php'; ?>'),
		resourceUrl: encodeURIComponent('<?php echo RESOURCE_URL . 'flash/'; ?>'),
		width: 800,
		version: '<?php echo RESOURCE_SIGN;?>'
	},
	params = {
		allowScriptAccess: 'always',
		allowFullScreen: 'true',
		quality: 'high',
		wmode: 'opaque',
		bgcolor: '#000',
		menu: 'false'
	},
	attributes = {},
	callbackFn = function(data) {
		if (data.success) {
			log('flash loading success!');
		} else {
			log('flash loading fail!');
		}
		
	};
	swfobject.embedSWF(swfUrl, id, width, height, version, expressInstallSwfUrl, flashVars, params, attributes, callbackFn);
})();
</script>
<div style="display:none;"><script type="text/javascript" src="http://s24.cnzz.com/stat.php?id=3610670&web_id=3610670"></script></div>
</body>
</html>
