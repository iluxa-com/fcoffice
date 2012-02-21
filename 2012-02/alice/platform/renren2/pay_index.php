<?php
require_once '../../config.php';

$currentUser = App::getCurrentUser();
$userModel = new UserModel($currentUser['user_id']);
$dataArr = $userModel->hMget(array('username', 'head_img', 'gold'));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>童话迷城</title>
<script type="text/javascript" src="<?php echo RESOURCE_URL; ?>js/jquery-1.5.min.js"></script>
<script type="text/javascript" src="http://static.connect.renren.com/js/v1.0/FeatureLoader.jsp"></script>
</head>
<style type="text/css">
body, html, ul, li, form, img{
	padding:0px;
	margin:0px;
	boder:0px;
	list-style:none;
}
body {
	font-size:12px;
	margin:0px auto;
}
#top {
	background:#005eac;
	color:#ffa153;
	padding:15px 0;
	margin-bottom:6px;
	font-size:13px;
	font-weight:bold;
}
.userInfo {
    font-size:12px;
    font-weight:bold;
    line-height: 150%;
    margin-top:6px;
}
.userInfo .head {
    margin:0 6px 6px 6px;
}
.userInfo .username {
    color:#000;
}
.userInfo .gold {
    color:#f00;
}
.layout{
	width:798px;
	height:680px;
}
.list{
	width:440px;
	height:240px;
	margin:10px auto;
}
.list .change{
	float:left;
	width:145px; 
	height:185px;
	margin:0px 25px;
	text-align:center;
}
.list .change a{
	display:block;
	width:70px;
	height:26px;
	margin:132px auto 30px auto;
}
.list1{
	width:600px;
	height:240px;
	margin:10px auto;
}
.list1 .change{
	float:left;
	width:165px; 
	height:200px;
	margin:0px 15px;
	text-align:center;
}
.list1 .change a{
	display:block;
	width:70px;
	height:26px;
}
.layout span{
	color:#FF0000;
	font-weight:bold;
}
</style>
<body>
<?php require_once 'header.inc.php'; ?>
<div class="layout" style="background:url('<?php echo RESOURCE_URL; ?>images/pay_bg.jpg') no-repeat">
	<div class="userInfo"><div class="head" style="float:left;"><img src="<?php echo $dataArr['head_img']; ?>" /></div><div style="float:left;"><div class="username"><?php echo $dataArr['username'];?></div>FH币余额：<b class="gold"><?php echo $dataArr['gold'];?></b></div><div style="clear:both;"></div></div>
	<div class="list">
		<div class="change" style="background:url('<?php echo RESOURCE_URL; ?>images/pay_10.png') no-repeat">
			<a class="recharge" href="javascript:void(0);" onclick="doRecharge(1);"></a>
			<div><span>1</span>人人豆</div>
		</div>
		<div class="change" style="background:url('<?php echo RESOURCE_URL; ?>images/pay_100.png') no-repeat;width:165px; height:200px;">
			<a class="recharge" style="margin:155px auto 20px 63px;" href="javascript:void(0);" onclick="doRecharge(10);"></a>
			<div><span>10</span>人人豆</div>
		</div>
	</div>
	<div class="list1">
		<div class="change" style="background:url('<?php echo RESOURCE_URL; ?>images/pay_200.png') no-repeat">
			<a class="recharge" href="javascript:void(0);" onclick="doRecharge(20);" style="margin:148px auto 30px 53px;"></a>
			<div><span>20</span>人人豆</div>
		</div>
		<div class="change" style="background:url('<?php echo RESOURCE_URL; ?>images/pay_500.png') no-repeat">
			<a class="recharge" href="javascript:void(0);" onclick="doRecharge(50);" style="margin:150px auto 30px 58px;"></a>
			<div><span>50</span>人人豆</div>
		</div>
		<div class="change" style="background:url('<?php echo RESOURCE_URL; ?>images/pay_1000.png') no-repeat">
			<a class="recharge" href="javascript:void(0);" onclick="doRecharge(100);" style="margin:148px auto 30px 55px;"></a>
			<div><span>100</span>人人豆</div>
		</div>
	</div>
</div>
<form id="payForm" action="http://app.renren.com/pay/web4test/submitOrder" method="post" target="_top">
  <input type="hidden" name="app_id" value="158035" />
  <input type="hidden" name="order_number" />
  <input type="hidden" name="token" />
  <input type="hidden" name="redirect_url" value="<?php echo GATEWAY_URL; ?>platform/renren2/pay_index.php" />
</form>
<script type="text/javascript">
XN_RequireFeatures(["Connect"], function(){
	XN.Main.init("5c0dc37fc13d4f239ce10d6ed2def64e", "<?php echo GATEWAY_URL; ?>platform/renren/xd_receiver.html");
});
</script>
<script type="text/javascript">
function doRecharge(amount) {
	$.post(
		'<?php echo GATEWAY_URL; ?>platform/renren2/pay_reg_order.php?amount=' + amount,
		'',
		function(data, textStatus, xhr) {
			if (data.token && data.order_id) {
				$('input[name=token]').val(data.token);
				$('input[name=order_number]').val(data.order_id);
				$('#payForm').submit();
			} else {
				alert('注册订单失败(' + xhr.responseText + ')');
			}
		},
		'json'
	);
}
</script>
</body>
</html>