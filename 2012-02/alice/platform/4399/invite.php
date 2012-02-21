<?php
require_once '../../config.php';
?>
<div id="nav"><img src="<?php echo RESOURCE_URL; ?>images/banner2.jpg" width="800" height="80" border="0" usemap="#Map" /></div>
<map name="Map" id="Map">
  <area shape="poly" coords="196,54,287,38,283,10,194,23" href="http://api.my.4399.com/100223/" alt="开始" />
  <area shape="poly" coords="311,46,405,58,410,29,319,17" href="javascript:void(0);" onclick="FH.inviteFriend();" alt="邀请" />
  <area shape="poly" coords="437,45,536,39,536,7,436,12" href="javascript:void(0);" alt="充值" />
  <area shape="poly" coords="560,49,653,59,656,32,563,20" href="http://my.4399.com/space-mtag-tagid-81173.html" target="_blank" alt="讨论" />
  <area shape="poly" coords="678,51,772,42,770,12,678,22" href="http://my.4399.com/space-361183138-do-thread-id-2224033-tagid-81173.html" target="_blank" alt="帮助" />
</map>
<my:request-form method="POST" type='童话迷城' invite="true" content="《童话迷城》很好玩哦，赶快加入吧！" id="myformb" action="/">
    <my:multi-friend-selector actiontext="邀请好友一起加入《童话迷城》" showborder="false" rows="6" max="6" exclude_friends_added="true" />
</my:request-form>