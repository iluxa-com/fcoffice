<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;道具数据管理&nbsp;&gt;&gt;&nbsp;道具列表</div>
<div class="gMain">
  <form class="gFrm ajaxSubmit" action="../gateway.php" method="get" ajaxTarget="#ajaxContainer">
    <input type="hidden" name="service" value="ItemDataService" />
    <input type="hidden" name="action" value="showList" />
    <table class="gTable" width="100%" border="0" cellpadding="0" cellspacing="1">
      <tr>
        <td class="td1">道具查询：</td>
        <td class="td2" style="text-align:left;padding-left:6px;"><select name="key">
            <option value="">－请选择－</option>
            <option value="item_id"<?php if($D['key'] == 'item_id'){echo 'selected="selected"';}?>>道具编号</option>
            <option value="item_name"<?php if($D['key'] == 'item_name'){echo 'selected="item_name"';}?>>道具名称</option>
          </select>
          <input type="text" name="val" value="<?php echo $D['val'];?>" style="width:200px;" />
        <td class="td3"><button type="submit">查询</button>
          <button class="gGoBack" type="button">返回</button></td>
      </tr>
    </table>
  </form>
  <table class="gTable gHover tableSorter itemList" style="margin-top:6px;" width="100%" border="0" cellpadding="0" cellspacing="1">
    <thead>
      <tr>
        <th class="item_id">Item ID</th>
        <th class="item_name">道具名称</th>
        <th class="link_name">链接名</th>
        <th class="buyable">能否购买</th>
        <th class="useable">能否使用</th>
        <th class="clickable">能否单击</th>
        <th class="dbclickable">能否双击</th>
        <th class="dragable">能否拖动</th>
        <th class="grade">等级限制</th>
        <th class="silver">金币单价</th>
        <th class="gold">FH币单价</th>
        <th class="operation hack">操作</th>
      </tr>
    </thead>
    <tbody>
<?php
if (empty($D['data'])) {
    echo '<tr><td colspan="12">尚未添加任何道具</td></tr>';
} else {
    foreach($D['data'] as $row) {
        echo <<<EOT
      <tr>
        <td>{$row['item_id']}</td>
        <td>{$row['item_name']}</td>
        <td>{$row['link_name']}</td>
        <td>{$row['buyable']}</td>
        <td>{$row['useable']}</td>
        <td>{$row['clickable']}</td>
        <td>{$row['dbclickable']}</td>
        <td>{$row['dragable']}</td>
        <td>{$row['grade']}</td>
        <td>{$row['silver']}</td>
        <td>{$row['gold']}</td>
        <td><a href="../gateway.php?service=ItemDataService&action=showUpdate&id={$row['item_id']}" ajaxTarget="#ajaxContainer">修改</a></td>
      </tr>
      <tr class="description" style="display:none;">
        <td colspan="13" style="text-align:left;line-height:150%;padding:0 6px;">道具描述：{$row['description']}</td>
      </tr>
      <tr class="extra_info" style="display:none;">
        <td colspan="13" style="text-align:left;line-height:150%;padding:0 6px;">附加信息：{$row['extra_info']}</td>
      </tr>
EOT;
    }
}
?>
    </tbody>
  </table>
  <div style="margin-top:6px;"><button onclick="$('tr.extra_info, tr.description').toggle();">隐藏/显示道具描述和附加信息</button></div>
  <div class="gPageList">
    <div class="stat">当前 <?php echo $D['page'];?>/<?php echo $D['totalPage'];?> 页 每页 <?php echo $D['pageSize'];?> 条 总共 <?php echo $D['count'];?> 条</div>
    <div class="list">
      <?php
      $urlPrefix = '../gateway.php?service=ItemDataService&action=showList&orderColumn=' . $D['orderColumn'] . '&orderMethod=' . $D['orderMethod'] . '&key=' . urlencode($D['key']) . '&val=' . $D['val'] . '&page=';
      echo Pager::getPageList($urlPrefix, $D['page'], $D['totalPage'], 5, 'cur', ' ajaxTarget="#ajaxContainer"');
?>
    </div>
  </div>
</div>
<script type="text/javascript">
$(function(){
	var urlPrefix = '../gateway.php?service=ItemDataService&action=showList';
	var orderColumn = '<?php echo $D['orderColumn']; ?>';
	var orderMethod = '<?php echo $D['orderMethod']; ?>';
	$.applyTableSorter(urlPrefix, orderColumn, orderMethod);
});
</script>