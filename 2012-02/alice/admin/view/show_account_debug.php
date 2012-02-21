<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;用户数据管理&nbsp;&gt;&gt;&nbsp;帐号调试</div>
<div class="gMain">
  <form class="gFrm" action="../gateway.php?service=AdminService&action=doAccountDebug" method="post" target="_blank">
    <table class="gTable" width="100%" border="0" cellpadding="0" cellspacing="1">
      <tr>
        <td class="td1">User ID：</td>
        <td class="td2"><input type="text" name="user_id" class="positiveIntegerFilter" /></td>
        <td class="td3">说明：用户ID。</td>
      </tr>
    </table>
    <table class="gSubmit" width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="td1">&nbsp;</td>
        <td class="td2"><button type="submit">调试</button>
          <button class="gGoBack" type="button">返回</button></td>
        <td class="td3">&nbsp;</td>
      </tr>
    </table>
  </form>
  <ol>
    <li><b>操作说明：</b></li>
    <li>１、用自己的帐号登录游戏(成功进到游戏之后，就可以关掉)；</li>
    <li>２、在此页面输入要调试的帐号进行调试；</li>
    <li>３、调试时看到的数据，除好友列表可能是第１步登录帐号的，其他都是被调试帐号的；</li>
    <li>４、当加载的好友列表不是被调试帐号本身的好友列表时，切勿进行好友交互操作，以免数据错乱；</li>
    <li>５、调试时，如果被调试的帐号在线，会把它挤下线；</li>
    <li>６、此工具仅用于内部调试；</li>
  </ol>
</div>
