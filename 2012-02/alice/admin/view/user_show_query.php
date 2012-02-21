<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;用户数据管理&nbsp;&gt;&gt;&nbsp;信息查询</div>
<div class="gMain">
  <form class="gFrm ajaxSubmit" action="../gateway.php" method="get" ajaxTarget="#ajaxContainer">
    <input type="hidden" name="service" value="UserDataService" />
    <table class="gTable" width="100%" border="0" cellpadding="0" cellspacing="1">
      <tr>
        <td class="td1">查询类型：</td>
        <td class="td2" style="text-align:left;padding-left:6px;"><select name="action">
            <option value="">－请选择－</option>
            <option value="showUser">用户信息</option>
            <option value="showBag">背包信息</option>
          </select>
        <td class="td3">说明：查询类型。</td>
      </tr>
      <tr>
        <td class="td1">User ID：</td>
        <td class="td2"><input type="text" name="user_id" class="positiveIntegerFilter" /></td>
        <td class="td3">说明：用户ID。</td>
      </tr>
    </table>
    <table class="gSubmit" width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="td1">&nbsp;</td>
        <td class="td2"><button type="submit">查询</button>
          <button class="gGoBack" type="button">返回</button></td>
        <td class="td3">&nbsp;</td>
      </tr>
    </table>
  </form>
  <form class="gFrm ajaxSubmit" action="../gateway.php" method="get">
    <input type="hidden" name="service" value="UserDataService" />
    <table class="gTable" width="100%" border="0" cellpadding="0" cellspacing="1">
      <tr>
        <td class="td1">操作类型：</td>
        <td class="td2" style="text-align:left;padding-left:6px;"><select name="action">
            <option value="">－请选择－</option>
            <option value="doReset">帐号重置</option>
          </select>
        <td class="td3">说明：操作类型。</td>
      </tr>
      <tr>
        <td class="td1">User ID：</td>
        <td class="td2"><input type="text" name="user_id" class="positiveIntegerFilter" /></td>
        <td class="td3">说明：用户ID。</td>
      </tr>
    </table>
    <table class="gSubmit" width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="td1">&nbsp;</td>
        <td class="td2"><button type="button" class="btnSubmit">执行</button>
          <button class="gGoBack" type="button">返回</button></td>
        <td class="td3">&nbsp;</td>
      </tr>
    </table>
  </form>
</div>
<script type="text/javascript">
$(function(){
	$('.btnSubmit').click(function() {
		var obj = $(this).closest('form').find('select[name=action]');
		if (obj.val() == '') {
			alert('请选择操作类型！');
			obj.focus();
			return;
		}
		obj = $(this).closest('form').find('input[name=user_id]');
		if (obj.val() == '') {
			alert('请输入正确的用户ID！');
			obj.focus();
			return;
		}
		if (window.confirm('确定要执行此操作吗？')) {
			$(this).closest('form').submit();
		}
	});
});
</script>
