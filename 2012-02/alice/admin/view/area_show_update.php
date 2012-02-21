<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;地区数据管理&nbsp;&gt;&gt;&nbsp;修改地区</div>
<div class="gMain">
  <form class="gFrm ajaxSubmit" action="../gateway.php?service=AreaDataService&action=doUpdate&id=<?php echo $D['data']['area_id'];?>" method="post">
    <table class="gTable" width="100%" border="0" cellpadding="0" cellspacing="1">
      <tr>
        <td class="td1">Zone ID：</td>
        <td class="td2"><input type="text" name="zone_id" class="positiveIntegerFilter" value="<?php echo $D['data']['zone_id'];?>" /></td>
        <td class="td3">说明：区域ID。</td>
      </tr>
      <tr>
        <td class="td1">Place ID：</td>
        <td class="td2"><input type="text" name="place_id" class="positiveIntegerFilter" value="<?php echo $D['data']['place_id'];?>" /></td>
        <td class="td3">说明：地点ID，必须唯一。</td>
      </tr>
      <tr>
        <td class="td1">Area ID：</td>
        <td class="td2"><input type="text" name="area_id" class="positiveIntegerFilter" value="<?php echo $D['data']['area_id'];?>" disabled="disabled" /></td>
        <td class="td3">说明：地区ID，必须唯一。</td>
      </tr>
      <tr>
        <td class="td1">Area Name：</td>
        <td class="td2"><input type="text" name="name" value="<?php echo $D['data']['name'];?>" /></td>
        <td class="td3">说明：地区名称。</td>
      </tr>
      <tr>
        <td class="td1">地区描述：</td>
        <td class="td2"><input type="text" name="description" value="<?php echo $D['data']['description'];?>" /></td>
        <td class="td3">说明：地区描述。</td>
      </tr>
      <tr>
        <td class="td1">等级限制：</td>
        <td class="td2"><input type="text" name="need_grade" class="positiveIntegerFilter" value="<?php echo $D['data']['need_grade'];?>" /></td>
        <td class="td3">说明：等级限制。</td>
      </tr>
      <tr>
        <td class="td1">是否开放：</td>
        <td class="td2"><input type="text" name="is_open" class="positiveIntegerFilter" value="<?php echo $D['data']['is_open'];?>" /></td>
        <td class="td3">说明：是否开放(0=关闭,1=开放)。</td>
      </tr>
    </table>
    <table class="gSubmit" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td class="td1">&nbsp;</td>
        <td class="td2">
          <button type="submit">修改</button>
          <button class="gGoBack" type="button">返回</button></td>
        <td class="td3">&nbsp;</td>
     </tr>
    </table>
  </form>
</div>

