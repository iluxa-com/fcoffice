<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>梦境迷城</title>
<script type="text/javascript" src="<?php echo RESOURCE_URL; ?>js/swfobject.min.js?v=1.0"></script>
<script type="text/javascript" src="<?php echo RESOURCE_URL; ?>js/global.js?v=1.2"></script>
<script type="text/javascript" src="<?php echo RESOURCE_URL; ?>js/devel.js?v=1.0"></script>
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
	width:800px;
	margin:0 auto;
}
#top {
	background:#005eac;
	color:#ffa153;
	padding:10px 0;
	margin-bottom:6px;
	font-size:13px;
	font-weight:bold;
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
	line-height:150%;
}
</style>
</head>
<body>
<div id="top">《梦境迷城》－本地测试环境</div>
<div id="main">
  <noscript>
    <object id="flashDiv" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="800" height="680">
        <param name="movie" value="http://alice-res.fanhougame.com/alice_new/flash1/PreLoading-20110930.swf" />
        <param name="quality" value="high" />
        <param name="wmode" value="opaque" />
        <param name="swfversion" value="9.0.45.0" />
        <!-- 下一个对象标签用于非 IE 浏览器。所以使用 IECC 将其从 IE 隐藏。 -->
        <!--[if !IE]>-->
        <object width="800" height="680" id="flashDiv" type="application/x-shockwave-flash" data="http://alice-res.fanhougame.com/alice_new/flash1/PreLoading-20110930.swf" style="visibility: visible;">
            <!--<![endif]-->
            <param name="allowScriptAccess" value="always" />
            <param name="allowFullScreen" value="true" />
            <param name="quality" value="high" />
            <param name="wmode" value="gpu" />
            <param name="bgcolor" value="#000" />
            <param name="menu" value="false" />
            <param name="flashvars" value="<?PHP echo htmlentities("platform=devel&gatewayUrl=".urlencode(GATEWAY_URL.'gateway.php')."&resourceUrl=".urlencode(RESOURCE_URL.'flash1/')."&width=800&version=".RESOURCE_SIGN);?>" />
            <!--[if !IE]>-->
            </object>
        <!--<![endif]-->
    </object>
    <script type="text/javascript">
    swfobject.registerObject("flashDiv");
    </script>
    Sorry,your browser does not support or enable JavaScript!
  </noscript>
  <div id="flashDiv">loading...</div>
</div>
<div id="footer">
  <div>《梦境迷城》　您的用户ID：<?php echo $userId; ?></div>
  <div><b>声明：此应用由“<a href="http://www.fanhougame.com/?ref=alice_devel" target="_blank">饭后游戏</a>”提供，若您在游戏中遇到问题，可以直接联系“<a href="http://wpa.qq.com/msgrd?v=1&uin=2270736540&site=alice&menu=yes" target="_blank">在线客服</a>”，客服人员将会尽快为您解答。</b></div>
  <div><span style="color:#f00;">健康忠告：</span>抵制不良游戏，拒绝盗版游戏。注意自我保护，谨防受骗上当。适度游戏益脑，沉迷游戏伤身。合理安排时间，享受健康生活。</div>
  <div><a href="http://192.168.0.222/">更换版本</a> | <a href="platform/devel/account.php">更换帐号</a> | <a href="admin/login.php" target="_blank">登录后台</a> | <a href="http://newui.com/alice/Editor/AliceEditor/login.php?movie=QWxpY2VFZGl0b3Iuc3dm" target="_blank">编辑器</a> | <a href="http://newui.com/alice/Editor/AliceEditor/login.php?movie=QWxpY2VFZGl0b3JOZXcuc3dm" target="_blank">编辑器(新关卡)</a> | <a href="http://newui.com/alice/Editor/AliceEditor/login.php?movie=QWxpY2VFZGl0b3JOZXcxLnN3Zg==" target="_blank">编辑器(新关卡2)</a></div>
  <div>gatewayUrl：<b id="gatewayUrl"></b></div>
  <div>resourceUrl：<b id="resourceUrl"></b></div>
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
		wmode: 'gpu',
		bgcolor: '#000',
		menu: 'false'
	},
	attributes = {},
	callbackFn = function(data) {
		if (data.success) {
			document.getElementById('gatewayUrl').innerHTML = decodeURIComponent(flashVars.gatewayUrl);
			document.getElementById('resourceUrl').innerHTML = decodeURIComponent(flashVars.resourceUrl);
            //document.getElementById('flashDiv').CallJs.authorize('test');
			log('flash loading success!');
		} else {
			log('flash loading fail!');
		}
		
	};
	swfobject.embedSWF(swfUrl, id, width, height, version, expressInstallSwfUrl, flashVars, params, attributes, callbackFn);
})();
</script>
</body>
</html>
