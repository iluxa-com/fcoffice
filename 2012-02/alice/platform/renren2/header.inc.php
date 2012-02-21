<?php
if (defined('IS_TEST_SERVER')) {
    echo <<<EOT
<div id="top">《童话迷城》－人人网测试环境</div>
EOT;
}
?>
<!--<div style="margin-bottom:6px;"><a href="http://apps.renren.com/happy_shop/?ref=alice_renren2" target="_blank"><img src="<?php echo RESOURCE_URL; ?>images/happy_shop.jpg?v=1.0" /></a></div>-->
<div style="margin-bottom:6px;"><img src="<?php echo RESOURCE_URL; ?>images/banner2.jpg?v=1.0" width="800" height="80" border="0" usemap="#Map" /></div>
<map name="Map" id="Map">
  <area shape="poly" coords="192,52,283,36,279,8,190,21" href="javascript:void(0);" onclick="self.location.reload();" alt="开始" />
  <area shape="poly" coords="305,44,397,53,404,27,313,15" href="javascript:void(0);" onclick="FH.inviteFriend();" alt="邀请" />
  <area shape="poly" coords="432,43,531,37,531,5,431,10" href="javascript:void(0);" alt="充值" />
  <area shape="poly" coords="555,46,648,56,651,29,558,17" href="http://page.renren.com/699158035" target="_blank" alt="讨论" />
  <area shape="poly" coords="673,46,767,37,765,9,673,19" href="http://page.renren.com/699146821/group/334448096" target="_blank" alt="帮助" />
</map>