<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;用户数据管理&nbsp;&gt;&gt;&nbsp;数据修改</div>
<div class="gMain">
  <form class="gFrm ajaxSubmit dataModifyTable" action="../gateway.php?service=UserDataService&action=doDataModify" method="post">
    <table class="gTable" width="100%" border="0" cellpadding="0" cellspacing="1">
      <tr>
        <td class="td1">User ID：</td>
        <td class="td2"><input type="text" name="user_id" class="positiveIntegerFilter" /></td>
        <td class="td3">说明：用户ID。</td>
      </tr>
      <tr>
        <td class="td1">金币：</td>
        <td class="td2"><input type="text" name="silver" class="negativeIntegerFilter" maxlength="9" /></td>
        <td class="td3">说明：金币，整数。</td>
      </tr>
      <tr>
        <td class="td1">FH币：</td>
        <td class="td2"><input type="text" name="gold" class="negativeIntegerFilter" maxlength="9" /></td>
        <td class="td3">说明：FH币，整数。</td>
      </tr>
      <tr>
        <td class="td1">累积经验：</td>
        <td class="td2"><input type="text" name="exp" class="positiveIntegerFilter" maxlength="8" /></td>
        <td class="td3">说明：累积经验，整数。</td>
      </tr>
      <tr>
        <td class="td1">经验增减：</td>
        <td class="td2"><input type="text" name="exp_incr" class="negativeIntegerFilter" maxlength="8" /></td>
        <td class="td3">说明：经验增减，整数，正数表示增加，负数表示减少。</td>
      </tr>
      <tr>
        <td class="td1">体力同步：</td>
        <td class="td2"><input type="text" name="energy_time" class="positiveIntegerFilter" maxlength="3" /></td>
        <td class="td3">说明：体力最后同步时间，<a href="../tools/time_converter.php" target="_blank">Unix时间戳</a>，整数。</td>
      </tr>
      <tr>
        <td class="td1">祝福点数：</td>
        <td class="td2"><input type="text" name="benison" class="positiveIntegerFilter" maxlength="2" /></td>
        <td class="td3">说明：祝福点数，整数。</td>
      </tr>
      <tr>
        <td class="td1">魅力点数：</td>
        <td class="td2"><input type="text" name="charm" class="positiveIntegerFilter" maxlength="9" /></td>
        <td class="td3">说明：魅力点数，整数。</td>
      </tr>
      <tr>
        <td class="td1">爱心点数：</td>
        <td class="td2"><input type="text" name="heart" class="positiveIntegerFilter" maxlength="9" /></td>
        <td class="td3">说明：爱心点数，整数。</td>
      </tr>
      <tr>
        <td class="td1">Gender：</td>
        <td class="td2"><input type="text" name="gender" class="negativeIntegerFilter" maxlength="2" /></td>
        <td class="td3">说明：性别(-1=未设置，0=女孩，1=男孩)。</td>
      </tr>
      <tr>
        <td class="td1">称号：</td>
        <td class="td2"><input type="text" name="title" class="positiveIntegerFilter" maxlength="2" /></td>
        <td class="td3">说明：称号ID，整数。</td>
      </tr>
      <!--<tr>
        <td class="td1">连续登录：</td>
        <td class="td2"><input type="text" name="continue_times" class="positiveIntegerFilter" maxlength="1" /></td>
        <td class="td3">说明：连续登录次数(0-5)，整数。</td>
      </tr>
      <tr>
        <td class="td1">最后登录：</td>
        <td class="td2"><input type="text" name="last_login" class="positiveIntegerFilter" maxlength="10" /></td>
        <td class="td3">说明：最后登录时间，Unix时间戳，整数。</td>
      </tr>-->
      <tr>
        <td class="td1">道具数量：</td>
        <td class="td2"><input type="text" name="item" /></td>
        <td class="td3">说明：格式固定为“item_id,num”，如：“5001,10”。</td>
      </tr>
      <!--<tr>
        <td class="td1">添加宠物：</td>
        <td class="td2"><input type="text" name="pet" /></td>
        <td class="td3">说明：宠物的ID，如：“9001”。</td>
      </tr>-->
      <tr>
        <td class="td1">荣誉次数：</td>
        <td class="td2"><input type="text" name="credit" /></td>
        <td class="td3">说明：格式固定为“credit_id,credit_times”，如：“1,300”。</td>
      </tr>
      <tr>
        <td class="td1">闯关次数：</td>
        <td class="td2"><input type="text" name="level_record" /></td>
        <td class="td3">说明：格式固定为“area_id,challenge_times”，如：“2,30”。</td>
      </tr>
      <tr>
        <td class="td1">添加任务：</td>
        <td class="td2"><input type="text" name="add_task" /></td>
        <td class="td3">说明：任务ID，多个之间用逗号分隔，如：“1”、“1,2”。</td>
      </tr>
      <tr>
        <td class="td1">删除任务：</td>
        <td class="td2"><input type="text" name="del_task" /></td>
        <td class="td3">说明：任务ID，多个之间用逗号分隔，如：“1”、“1,2”。</td>
      </tr>
      <tr>
        <td class="td1">闯指定关：</td>
        <td class="td2"><input type="text" name="level_id" /></td>
        <td class="td3">说明：关卡ID，如：“1”、“2”，设置“0”恢复正常闯关。</td>
      </tr>
      <tr>
        <td class="td1">普通进度：</td>
        <td class="td2"><input type="text" name="progress" /></td>
        <td class="td3">说明：普通模式闯关进度，格式：“<a href="../gateway.php?service=AreaDataService&action=showList" ajaxTarget="#ajaxContainer">地区编号</a>-节点编号-0”。</td>
      </tr>
      <tr>
        <td class="td1">挑战进度：</td>
        <td class="td2"><input type="text" name="progress2" /></td>
        <td class="td3">说明：挑战模式进度，格式：“<a href="../gateway.php?service=AreaDataService&action=showList" ajaxTarget="#ajaxContainer">地区编号</a>-节点编号”。</td>
      </tr>
      <tr>
        <td class="td1">签到次数：</td>
        <td class="td2"><input type="text" name="sign_times" /></td>
        <td class="td3">说明：正整数。</td>
      </tr>
      <tr>
        <td class="td1">最后签到：</td>
        <td class="td2"><input type="text" name="last_sign" /></td>
        <td class="td3">说明：最后签到时间，<a href="../tools/time_converter.php" target="_blank">Unix时间戳</a>，整数。</td>
      </tr>
    </table>
    <table class="gSubmit" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td class="td1">&nbsp;</td>
        <td class="td2">
          <button type="submit">修改</button>
          <button class="gGoBack" type="button">返回</button></td>
        <td class="td3">&nbsp;</td>
     </tr>
    </table>
  </form>
</div>
<script type="text/javascript">
function dataModifyCallback(data) {
	$('.dataModifyTable input').each(function() {
		var name = $(this).attr('name');
		if (data.status && typeof(data.status[name]) != 'undefined') {
			$(this).closest('tr').find('td').css({background:'red'});
		} else {
			$(this).val('');
		}
	});
	alert('操作成功，红色行表示修改失败的行！');
}
</script>
