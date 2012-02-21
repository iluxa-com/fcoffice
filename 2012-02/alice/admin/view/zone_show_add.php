<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;区域数据管理&nbsp;&gt;&gt;&nbsp;添加区域</div>
<div class="gMain">
  <form class="gFrm ajaxSubmit" action="../gateway.php?service=ZoneDataService&action=doAdd" method="post">
    <table class="gTable" width="100%" border="0" cellpadding="0" cellspacing="1">
      <tr>
        <td class="td1">Zone ID：</td>
        <td class="td2"><input type="text" name="zone_id" class="positiveIntegerFilter" /></td>
        <td class="td3">说明：区域ID，必须唯一。</td>
      </tr>
      <tr>
        <td class="td1">区域名称：</td>
        <td class="td2"><input type="text" name="name" /></td>
        <td class="td3">说明：区域名称。</td>
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
