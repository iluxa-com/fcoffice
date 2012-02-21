<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;道具数据管理&nbsp;&gt;&gt;&nbsp;添加道具</div>
<div class="gMain">
  <form class="gFrm ajaxSubmit" action="../gateway.php?service=ItemDataService&action=doUpdate&id=<?php echo $D['data']['item_id'];?>" method="post">
    <table class="gTable" width="100%" border="0" cellpadding="0" cellspacing="1">
      <tr>
        <td class="td1">Item ID：</td>
        <td class="td2"><input type="text" name="item_id" value="<?php echo $D['data']['item_id'];?>" disabled="disabled" /></td>
        <td class="td3">说明：道具ID，必须唯一。</td>
      </tr>
      <tr>
        <td class="td1">道具名称：</td>
        <td class="td2"><input type="text" name="item_name" value="<?php echo $D['data']['item_name'];?>" /></td>
        <td class="td3">说明：道具名称。</td>
      </tr>
      <tr>
        <td class="td1">附加信息：</td>
        <td class="td2"><textarea name="extra_info" cols="40" rows="6" readonly="readonly" class="jsonMaker hack"><?php echo $D['data']['extra_info'];?></textarea></td>
        <td class="td3">说明：json编码格式，如：{"event":"UseItem","params":[{"id":1,"num":1},{"id":2,"num":1}]}。</td>
      </tr>
      <tr>
        <td class="td1">道具描述：</td>
        <td class="td2"><textarea name="description" cols="40" rows="6"><?php echo $D['data']['description'];?></textarea></td>
        <td class="td3">说明：道具描述。</td>
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
