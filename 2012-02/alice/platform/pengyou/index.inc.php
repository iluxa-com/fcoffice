<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>迷城历险记</title>
<script type="text/javascript" src="<?php echo RESOURCE_URL; ?>js/swfobject.min.js?v=1.0"></script>
<script type="text/javascript" src="<?php echo RESOURCE_URL; ?>js/global.js?v=1.3"></script>
<script type="text/javascript" src="http://fusion.qq.com/fusion_loader?appid=27790&platform=pengyou" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_URL; ?>js/pengyou.js?v=1.5"></script>
<style type="text/css">
* {
	margin:0;
	padding:0;
}
a {
	color:#00f;
	text-decoration:none;
}
body {
	text-align:center;
	font-family:宋体;
	font-size:12px;
	width:760px;
	margin:0 auto;
}
#top {
	background:#005eac;
	color:#ffa153;
	padding:15px 0;
	margin-bottom:6px;
	font-size:13px;
	font-weight:bold;
}
#main {
	width:760px;
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
<div id="top">《迷城历险记》－腾讯朋友测试环境</div>
EOT;
}
?>
<div id="nav" style="margin-bottom:6px;"><img src="<?php echo RESOURCE_URL; ?>images/banner2_760.jpg" width="760" height="76" border="0" usemap="#Map" /></div>
<map name="Map" id="Map">
  <area shape="poly" coords="175,26,270,10,274,38,180,53" href="javascript:void(0);" onclick="self.location.reload();" alt="开始" />
  <area shape="poly" coords="294,9,395,20,387,48,291,37" href="javascript:void(0);" onclick="FH.inviteFriend();" alt="邀请" />
  <area shape="poly" coords="415,16,513,10,514,41,417,46" href="javascript:alert('对不起，此功能暂未开放！');" alt="充值" />
  <area shape="poly" coords="540,15,632,23,630,52,537,44" href="http://sobar.soso.com/b/3007483_1664" target="_blank" alt="讨论" />
  <area shape="poly" coords="655,19,747,9,748,36,655,47" href="javascript:alert('对不起，此功能暂未开放！');" alt="帮助" />
</map>
<div id="main">
  <noscript>
  Sorry,your browser does not support or enable JavaScript!
  </noscript>
  <div id="flashDiv">loading...</div>
</div>
<div id="footer">
  <div>《迷城历险记》　您的用户ID：<?php echo $userId; ?>　官方玩家交流群：<a href="http://qun.qq.com/#jointhegroup/gid/187283908" target="_blank">187283908</a>　<span id="txWB_W1"></span></div>
  <div><b>声明：此应用由“<a href="http://www.fanhougame.com/?ref=alice_pengyou" target="_blank">饭后游戏</a>”提供，若您在游戏中遇到问题，可以直接联系“<a href="http://wpa.qq.com/msgrd?v=1&uin=2369211077&site=alice&menu=yes" target="_blank">在线客服</a>”，客服人员将会尽快为您解答。</b></div>
  <div><span style="color:#f00;">健康忠告：</span>抵制不良游戏，拒绝盗版游戏。注意自我保护，谨防受骗上当。适度游戏益脑，沉迷游戏伤身。合理安排时间，享受健康生活。</div>
</div>
<iframe scrolling="no" frameborder="0" src="<?php echo GATEWAY_URL; ?>platform/pengyou/show.php?ref=<?php echo $userId;?>" style="width: 760px;height: 110px;margin: 0 auto;margin-top:6px;display:block;"></iframe>
<script type="text/javascript">
(function() {
	function log(str) {
		if (typeof(console) != 'undefined' && typeof(console.log) == 'function') {
			console.log(str);
		}
	}
	var swfUrl = '<?php echo RESOURCE_URL; ?>flash/PreLoading-<?php echo LOADING_SIGN; ?>.swf',
	id = 'flashDiv',
	width = '760',
	height = '680',
	version = '9.0.0',
	expressInstallSwfUrl = '',
	flashVars = {
		platform: '<?php echo $platform; ?>',
		gatewayUrl: encodeURIComponent('<?php echo GATEWAY_URL . 'gateway.php'; ?>'),
		resourceUrl: encodeURIComponent('<?php echo RESOURCE_URL . 'flash/'; ?>'),
		width: 760,
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
<script type="text/javascript">
var tencent_wb_name = "tonghuamicheng";
var tencent_wb_sign = "4a9bdb2746f669d59f085738cde28b222a8d8831";
var tencent_wb_style = "3";
</script>
<script type="text/javascript" src="http://v.t.qq.com/follow/widget.js" charset="utf-8"></script>
<div style="display:none;"><script type="text/javascript" src="http://s24.cnzz.com/stat.php?id=3610913&web_id=3610913"></script></div>
</body>
</html>
