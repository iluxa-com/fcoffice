<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;道具数据管理&nbsp;&gt;&gt;&nbsp;数据同步</div>
<div class="gMain">
  <form id="itemSyncFrm" class="gFrm ajaxSubmit" action="../gateway.php?service=ItemDataService&action=doSync" method="post">
    <p>此操作会先删除Redis数据库服务器中的旧道具数据，然后将MySQL数据库服务器中的新道具数据同步到Redis数据库服务器。</p>
    <p><font color="red">注意：同步前务必请确认MySQL数据库服务器中道具数据的正确性！操作有风险，请谨慎操作！</font></p>
    <p>&nbsp;</p>
    <p>
      <button type="submit">同步</button>
      <button class="gGoBack" type="button">返回</button>
    </p>
  </form>
</div>
<script type="text/javascript">
$(function(){
	$('#itemSyncFrm').data('validateCallback',function(){
		return window.confirm('确定要执行同步操作吗？');
	});
});
</script> 