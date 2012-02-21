<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;用户数据管理&nbsp;&gt;&gt;&nbsp;用户信息</div>
<div class="gMain">
  <form id="userUpdateFrm" class="gFrm ajaxSubmit" style="margin-top:6px;" action="../gateway.php?service=UserDataService&action=doUserUpdate&user_id=<?php echo $D['user_id'];?>" method="post">
    <table class="gTable" width="100%" border="0" cellpadding="0" cellspacing="1">
      <tr>
        <td class="td1">User ID：</td>
        <td class="td2"><input type="text" name="user_id" value="<?php echo $D['user']['user_id'];?>" disabled="disabled" /></td>
        <td class="td3">说明：用户ID。</td>
      </tr>
      <tr>
        <td class="td1">SNS ID：</td>
        <td class="td2"><input type="text" name="sns_uid" value="<?php echo $D['user']['sns_uid'];?>" disabled="disabled" /></td>
        <td class="td3">说明：SNS ID。</td>
      </tr>
      <tr>
        <td class="td1">性别：</td>
        <td class="td2"><input type="text" name="gender" value="<?php echo $D['user']['gender'];?>" class="positiveIntegerFilter" disabled="disabled" /></td>
        <td class="td3">说明：性别(-1=未设置,0=girl,1=boy)。</td>
      </tr>
      <tr>
        <td class="td1">平台姓名：</td>
        <td class="td2"><input type="text" name="username<?php echo $D['key_suffix']; ?>" value="<?php echo $D['user']['username' . $D['key_suffix']];?>" disabled="disabled" /></td>
        <td class="td3">说明：平台姓名。</td>
      </tr>
      <tr>
        <td class="td1">平台头像：</td>
        <td class="td2"><input type="text" name="head_img<?php echo $D['key_suffix']; ?>" value="<?php echo $D['user']['head_img' . $D['key_suffix']];?>" disabled="disabled" /></td>
        <td class="td3">说明：平台头像，<a href="<?php echo $D['user']['head_img' . $D['key_suffix']];?>" target="_blank">点击查看</a>。</td>
      </tr>
      <tr>
        <td class="td1">Title：</td>
        <td class="td2"><input type="text" name="title" value="<?php echo $D['user']['title'];?>" class="positiveIntegerFilter" /></td>
        <td class="td3">说明：称号。</td>
      </tr>
      <tr>
        <td class="td1">Exp：</td>
        <td class="td2"><input type="text" name="exp" value="<?php echo $D['user']['exp'];?>" class="positiveIntegerFilter" disabled="disabled" /></td>
        <td class="td3">说明：经验值。</td>
      </tr>
      <tr>
        <td class="td1">Last_Exp：</td>
        <td class="td2"><input type="text" name="last_exp" value="<?php echo $D['user']['last_exp'];?>" class="positiveIntegerFilter" disabled="disabled" /></td>
        <td class="td3">说明：上次升级经验值。</td>
      </tr>
      <tr>
        <td class="td1">体力同步：</td>
        <td class="td2"><input type="text" name="energy_time" value="<?php echo $D['user']['energy_time'];?>" class="positiveIntegerFilter" /></td>
        <td class="td3">说明：体力最后同步时间。</td>
      </tr>
      <tr>
        <td class="td1">Benison：</td>
        <td class="td2"><input type="text" name="benison" value="<?php echo $D['user']['benison'];?>" class="positiveIntegerFilter" disabled="disabled" /></td>
        <td class="td3">说明：祝福值。</td>
      </tr>
      <tr>
        <td class="td1">Charm：</td>
        <td class="td2"><input type="text" name="charm" value="<?php echo $D['user']['charm'];?>" class="positiveIntegerFilter" disabled="disabled" /></td>
        <td class="td3">说明：魅力值。</td>
      </tr>
      <tr>
        <td class="td1">Heart：</td>
        <td class="td2"><input type="text" name="heart" value="<?php echo $D['user']['heart'];?>" class="positiveIntegerFilter" disabled="disabled" /></td>
        <td class="td3">说明：爱心值。</td>
      </tr>
      <tr>
        <td class="td1">金币：</td>
        <td class="td2"><input type="text" name="silver" value="<?php echo $D['user']['silver'];?>" class="positiveIntegerFilter" disabled="disabled" /></td>
        <td class="td3">说明：金币。</td>
      </tr>
      <tr>
        <td class="td1">FH币：</td>
        <td class="td2"><input type="text" name="gold" value="<?php echo $D['user']['gold'];?>" class="positiveIntegerFilter" disabled="disabled" /></td>
        <td class="td3">说明：FH币。</td>
      </tr>
      <tr>
        <td class="td1">feed_times：</td>
        <td class="td2"><input type="text" name="feed_times：" value="<?php echo $D['user']['feed_times'];?>" class="positiveIntegerFilter" /></td>
        <td class="td3">说明：今天可吃食物剩余次数。</td>
      </tr>
      <tr>
        <td class="td1">许愿道具：</td>
        <td class="td2"><input type="text" name="wish_items" value="<?php echo $D['user']['wish_items'];?>" /></td>
        <td class="td3">说明：最多5个，多个之间用逗号分隔。</td>
      </tr>
      <tr>
        <td class="td1">闯关进度：</td>
        <td class="td2"><input type="text" name="progress" value="<?php echo $D['user']['progress'];?>" disabled="disabled" /></td>
        <td class="td3">说明：普通模式闯关进度。</td>
      </tr>
      <tr>
        <td class="td1">帐号状态：</td>
        <td class="td2"><input type="text" name="status" value="<?php echo $D['user']['status'];?>" disabled="disabled" /></td>
        <td class="td3">说明：帐号状态(0=封号,1=正常)。</td>
      </tr>
      <tr>
        <td class="td1">最后登录：</td>
        <td class="td2"><input type="text" name="last_login" value="<?php echo $D['user']['last_login'];?>" /></td>
        <td class="td3">说明：最后登录时间。</td>
      </tr>
      <tr>
        <td class="td1">创建时间：</td>
        <td class="td2"><input type="text" name="create_time" value="<?php echo $D['user']['create_time'];?>" disabled="disabled" /></td>
        <td class="td3">说明：用户创建时间。</td>
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
