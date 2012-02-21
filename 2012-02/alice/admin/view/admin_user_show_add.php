<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;后台用户管理&nbsp;&gt;&gt;&nbsp;添加用户</div>
<div class="gMain">
  <form class="gFrm ajaxSubmit" action="../gateway.php?service=AdminUserService&action=doAdd" method="post">
    <table class="gTable" width="100%" border="0" cellpadding="0" cellspacing="1">
      <tr>
        <td class="td1">E-mail：</td>
        <td class="td2"><input type="text" name="email"/></td>
        <td class="td3">说明：E-mail，必须唯一。</td>
      </tr>
      <tr>
        <td class="td1">Password：</td>
        <td class="td2"><input type="password" name="password"/></td>
        <td class="td3">说明：密码。</td>
      </tr>
      <tr>
        <td class="td1">Name：</td>
        <td class="td2"><input type="text" name="name"/></td>
        <td class="td3">说明：姓名。</td>
      </tr>
      <tr>
        <td class="td1">Roles：</td>
        <td class="td2"><input type="text" name="roles" /></td>
        <td class="td3">说明：角色，多个之间用逗号分隔。</td>
      </tr>
      <tr>
        <td class="td1">Note：</td>
        <td class="td2"><input type="text" name="note" /></td>
        <td class="td3">说明：备注。</td>
      </tr>
    </table>
    <table class="gSubmit" width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="td1">&nbsp;</td>
        <td class="td2"><button type="submit">添加</button>
          <button class="gGoBack" type="button">返回</button></td>
        <td class="td3">&nbsp;</td>
      </tr>
    </table>
  </form>
</div>
