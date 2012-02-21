<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;系统相关链接&nbsp;&gt;&gt;&nbsp;环境切换</div>
<div class="gMain">
  <form class="gFrm ajaxSubmit switchEnvFrm" action="../gateway.php" method="get">
    <input type="hidden" name="service" value="AdminService" />
    <input type="hidden" name="action" value="doSwitchEnv" />
    <table class="gTable" width="100%" border="0" cellpadding="0" cellspacing="1">
      <tr>
        <td class="td1">当前环境：</td>
        <td class="td2"><div id="envInfo"><?php if($D['is_test_env']){echo '测试环境(RC)';}else{echo '正式环境(GA)';}?></div></td>
        <td class="td3"><button type="submit">切换</button></td>
      </tr>
    </table>
  </form>
</div>
<script type="text/javascript">
$(function() {
	$('.switchEnvFrm').data('validateCallback', function() {
		return window.confirm('确定要切换吗？')
	});
});
function ajaxCallback(data) {
	var info = '';
	if (data.is_test_env) {
		info = '测试环境(RC)';
	} else {
		info = '正式环境(GA)';
	}
	$('#envInfo').html(info);
}
</script>