<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<style type="text/css">
* {
	margin:0;
	padding:0;
}
body {
	text-align:center;
}
</style>
</head>
<body>
<img src="<?php echo RESOURCE_URL; ?>images/main.jpg" /> 
<script type="text/javascript" src="http://static.connect.renren.com/js/v1.0/FeatureLoader.jsp"></script> 
<script type="text/javascript">
XN_RequireFeatures(["Connect"], function(){
	XN.Main.init("aae474c5297b4a2caae6d0b65b06e33c", "<?php echo GATEWAY_URL; ?>platform/renren/xd_receiver.html");
	var callback = function(){
		top.location = "<?php echo APP_URL; ?>";
	};
	XN.Connect.showAuthorizeAccessDialog(callback);
});
</script>
</body>
</html>