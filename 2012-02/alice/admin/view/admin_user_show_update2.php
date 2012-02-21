<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;后台用户管理&nbsp;&gt;&gt;&nbsp;修改密码</div>
<div class="gMain">
  <form class="gFrm ajaxSubmit" action="../gateway.php?service=AdminUserService&action=doPasswordUpdate&email=<?php echo $D['email'];?>" method="post">
    <table class="gTable" width="100%" border="0" cellpadding="0" cellspacing="1">
      <tr>
        <td class="td1">E-mail：</td>
        <td class="td2"><input type="text" name="email" value="<?php echo $D['email'];?>" disabled="disabled" /></td>
        <td class="td3">说明：E-mail，必须是唯一。</td>
      </tr>
      <tr>
        <td class="td1">新密码：</td>
        <td class="td2"><input type="password" name="password1" /></td>
        <td class="td3">说明：新密码。</td>
      </tr>
      <tr>
        <td class="td1">重复新密码：</td>
        <td class="td2"><input type="password" name="password2" /></td>
        <td class="td3">说明：重复新密码。</td>
      </tr>
    </table>
    <table class="gSubmit" width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="td1">&nbsp;</td>
        <td class="td2"><button type="submit">修改</button>
          <button class="gGoBack" type="button">返回</button></td>
        <td class="td3">&nbsp;</td>
      </tr>
    </table>
  </form>
</div>
