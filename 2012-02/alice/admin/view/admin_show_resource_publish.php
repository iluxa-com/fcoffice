<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;系统相关链接&nbsp;&gt;&gt;&nbsp;资源发布</div>
<div class="gMain">
  <form class="gFrm ajaxSubmit" action="../gateway.php" method="get">
    <input type="hidden" name="service" value="AdminService" />
    <input type="hidden" name="action" value="doResourcePublish" />
    <input type="hidden" name="key" />
    <input type="hidden" name="val" />
    <table class="gTable" width="100%" border="0" cellpadding="0" cellspacing="1">
      <tr>
        <th colspan="3">测试环境(RC)</th>
      </tr>
      <tr>
        <td class="td1">加载签名：</td>
        <td class="td2"><input type="text" id="sign_rc_preloading" value="<?php echo $D['setting']['sign_rc_preloading']; ?>" /></td>
        <td class="td3"><button type="button" class="btnUpdate">更新</button>&nbsp;<button type="button" class="btnView">查看</button></td>
      </tr>
      <tr>
        <td class="td1">资源签名：</td>
        <td class="td2"><input type="text" id="sign_rc_config" value="<?php echo $D['setting']['sign_rc_config']; ?>" /></td>
        <td class="td3"><button type="button" class="btnUpdate">更新</button>&nbsp;<button type="button" class="btnView">查看</button></td>
      </tr>
    </table>
    <table class="gTable" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:36px;">
      <tr>
        <th colspan="3">正式环境(GA)</th>
      </tr>
      <tr>
        <td class="td1">加载签名：</td>
        <td class="td2"><input type="text" id="sign_ga_preloading" value="<?php echo $D['setting']['sign_ga_preloading']; ?>" /></td>
        <td class="td3"><button type="button" class="btnUpdate">更新</button>&nbsp;<button type="button" class="btnView">查看</button></td>
      </tr>
      <tr>
        <td class="td1">资源签名：</td>
        <td class="td2"><input type="text" id="sign_ga_config" value="<?php echo $D['setting']['sign_ga_config']; ?>" /></td>
        <td class="td3"><button type="button" class="btnUpdate">更新</button>&nbsp;<button type="button" class="btnView">查看</button></td>
      </tr>
    </table>
  </form>
  <div style="margin-top:6px;color:#f00;font-weight:bold;">注意：操作有风险，请谨慎操作！</div>
</div>
<script type="text/javascript">
$(function() {
	var resourceUrl = '<?php echo RESOURCE_URL; ?>';
	$('.gFrm button').click(function() {
		if($(this).hasClass('btnUpdate')) {
			if (window.confirm('确定要更新此签名吗？')) {
				var obj = $(this).closest('tr').find('input');
				if (obj.val() == '') {
					alert('请输入正确的签名！');
					return;
				}
				$('input[name=key]').val(obj.attr('id'));
				$('input[name=val]').val(obj.val());
				$(this).closest('form').submit();
			}
		} else if (($(this).hasClass('btnView'))) {
			var obj = $(this).closest('tr').find('input');
			if (obj.attr('id').indexOf('_preloading') != -1) {
				window.open(resourceUrl + 'flash/PreLoading-' + obj.val() + '.swf');
			} else {
				window.open(resourceUrl + 'flash/config-' + obj.val() + '.json');
			}
		}
	});
});
</script>