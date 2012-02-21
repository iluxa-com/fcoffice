<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>feed test</title>
</head>
<body>
<script type="text/javascript" src="http://alrr-res.fanhougame.com/alrr/js/renren.js?v=1.0"></script>
<script type="text/javascript" src="http://static.connect.renren.com/js/v1.0/FeatureLoader.jsp"></script>
<script type="text/javascript">
XN_RequireFeatures(["Connect"], function(){
	XN.Main.init("aae474c5297b4a2caae6d0b65b06e33c", "http://alrr.fanhougame.com/platform/renren/xd_receiver.html");
});
window.onload = function() {
    FH.sendFeed('gradeUp', [100]);
    //FH.sendFeed('exchangeItem', ["五彩石"]);
}
</script>
</body>
</html>
