<?php
session_start();
if (!isset($_SESSION['ALICE_EDITOR_ACL']) || empty($_SESSION['ALICE_EDITOR_ACL'])) {
    exit('please login first!');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>AliceEditor</title>
<script type="text/javascript" src="js/swfobject.min.js?v=1.0"></script>
<style type="text/css">
* {
	margin:0;
	padding:0;
}
html, body {
    height:100%;
}
</style>
</head>
<body>
<noscript>Sorry,your browser does not support or enable JavaScript!</noscript>
<div id="flashDiv">loading...</div>
<script type="text/javascript">
(function() {
	function log(str) {
		if (typeof(console) != 'undefined' && typeof(console.log) == 'function') {
			console.log(str);
		}
	}
	var swfUrl = '<?php echo $_GET['movie']; ?>',
	id = 'flashDiv',
	width = '100%',
	height = '100%',
	version = '10.0.0',
	expressInstallSwfUrl = '',
	flashVars = {},
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
