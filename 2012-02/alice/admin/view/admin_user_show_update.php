<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;后台用户管理&nbsp;&gt;&gt;&nbsp;修改用户</div>
<div class="gMain">
  <form class="gFrm ajaxSubmit" action="../gateway.php?service=AdminUserService&action=doUpdate&email=<?php echo $D['data']['email'];?>" method="post">
    <table class="gTable" width="100%" border="0" cellpadding="0" cellspacing="1">
      <tr>
        <td class="td1">E-mail：</td>
        <td class="td2"><input type="text" name="email" value="<?php echo $D['data']['email'];?>" disabled="disabled" /></td>
        <td class="td3">说明：E-mail，必须唯一。</td>
      </tr>
      <tr>
        <td class="td1">Password：</td>
        <td class="td2"><input type="password" name="password" value="********" disabled="disabled" /></td>
        <td class="td3">说明：密码。<a href="../gateway.php?service=AdminUserService&action=showPasswordUpdate&email=<?php echo $D['data']['email'];?>" ajaxTarget="#ajaxContainer">修改密码</a></td>
      </tr>
      <tr>
        <td class="td1">Name：</td>
        <td class="td2"><input type="text" name="name" value="<?php echo $D['data']['name']?>" /></td>
        <td class="td3">说明：姓名。</td>
      </tr>
      <tr>
        <td class="td1">Roles：</td>
        <td class="td2"><input type="text" name="roles" value="<?php echo $D['data']['roles']?>"/></td>
        <td class="td3">说明：角色，多个之间用逗号分隔。</td>
      </tr>
      <tr>
        <td class="td1">Note：</td>
        <td class="td2"><input type="text" name="note" value="<?php echo $D['data']['note']?>" /></td>
        <td class="td3">说明：备注。</td>
      </tr>
      <tr>
        <td class="td1">LastLogin：</td>
        <td class="td2"><input type="text" name="last_login" value="<?php echo date('Y-m-d H:i:s ', $D['data']['last_login']);?>" disabled="disabled" /></td>
        <td class="td3">说明：最后登录时间。</td>
      </tr>
      <tr>
        <td class="td1">LastIp：</td>
        <td class="td2"><input type="text" name="last_ip" value="<?php echo $D['data']['last_ip']?>" disabled="disabled" /></td>
        <td class="td3">说明：最后登录IP。</td>
      </tr>
      <tr>
        <td class="td1">LoginTimes：</td>
        <td class="td2"><input type="text" name="login_times" value="<?php echo $D['data']['login_times']?>" disabled="disabled" /></td>
        <td class="td3">说明：登录次数。</td>
      </tr>
      <tr>
        <td class="td1">CreateTime：</td>
        <td class="td2"><input type="text" name="create_time" value="<?php echo date('Y-m-d H:i:s', $D['data']['create_time']);?>" disabled="disabled" /></td>
        <td class="td3">说明：创建时间。</td>
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
