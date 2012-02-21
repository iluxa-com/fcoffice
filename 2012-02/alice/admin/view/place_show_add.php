<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;地点数据管理&nbsp;&gt;&gt;&nbsp;添加地点</div>
<div class="gMain">
  <form class="gFrm ajaxSubmit" action="../gateway.php?service=PlaceDataService&action=doAdd" method="post">
    <table class="gTable" width="100%" border="0" cellpadding="0" cellspacing="1">
      <tr>
        <td class="td1">Zone ID：</td>
        <td class="td2"><input type="text" name="zone_id" class="positiveIntegerFilter" /></td>
        <td class="td3">说明：区域ID。</td>
      </tr>
      <tr>
        <td class="td1">Place ID：</td>
        <td class="td2"><input type="text" name="place_id" class="positiveIntegerFilter" /></td>
        <td class="td3">说明：地点ID，必须唯一。</td>
      </tr>
      <tr>
        <td class="td1">Place Name：</td>
        <td class="td2"><input type="text" name="name" /></td>
        <td class="td3">说明：地点名称。</td>
      </tr>
      <tr>
        <td class="td1">等级限制：</td>
        <td class="td2"><input type="text" name="need_grade" class="positiveIntegerFilter" /></td>
        <td class="td3">说明：等级限制。</td>
      </tr>
      <tr>
        <td class="td1">类型：</td>
        <td class="td2"><input type="text" name="type" class="positiveIntegerFilter" /></td>
        <td class="td3">说明：类型(0=城镇,1=闯关)。</td>
      </tr>
    </table>
    <table class="gSubmit" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td class="td1">&nbsp;</td>
        <td class="td2">
          <button type="submit">添加</button>
          <button class="gGoBack" type="button">返回</button></td>
        <td class="td3">&nbsp;</td>
     </tr>
    </table>
  </form>
</div>

