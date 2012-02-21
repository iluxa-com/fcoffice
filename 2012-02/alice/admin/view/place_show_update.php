<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;地点数据管理&nbsp;&gt;&gt;&nbsp;修改地点</div>
<div class="gMain">
  <form class="gFrm ajaxSubmit" action="../gateway.php?service=PlaceDataService&action=doUpdate&id=<?php echo $D['data']['place_id'];?>" method="post">
    <table class="gTable" width="100%" border="0" cellpadding="0" cellspacing="1">
      <tr>
        <td class="td1">Zone ID：</td>
        <td class="td2"><input type="text" name="zone_id" class="positiveIntegerFilter" value="<?php echo $D['data']['zone_id'];?>" disabled="disabled" /></td>
        <td class="td3">说明：区域ID。</td>
      </tr>
      <tr>
        <td class="td1">Place ID：</td>
        <td class="td2"><input type="text" name="place_id" class="positiveIntegerFilter" value="<?php echo $D['data']['place_id']?>" disabled="disabled" /></td>
        <td class="td3">说明：地点ID，必须唯一。</td>
      </tr>
      <tr>
        <td class="td1">Place Name：</td>
        <td class="td2"><input type="text" name="name" value="<?php echo $D['data']['name']?>"/></td>
        <td class="td3">说明：地点名称。</td>
      </tr>
      <tr>
        <td class="td1">等级限制：</td>
        <td class="td2"><input type="text" name="need_grade" class="positiveIntegerFilter" value="<?php echo $D['data']['need_grade']?>" /></td>
        <td class="td3">说明：等级限制。</td>
      </tr>
      <tr>
        <td class="td1">类型：</td>
        <td class="td2"><input type="text" name="type" class="positiveIntegerFilter" value="<?php echo $D['data']['type']?>" /></td>
        <td class="td3">说明：类型(0=城镇,1=闯关)。</td>
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
