<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;区域数据管理&nbsp;&gt;&gt;&nbsp;区域列表</div>
<div class="gMain">
  <table class="gTable gHover tableSorter zoneList" width="100%" border="0" cellpadding="0" cellspacing="1">
    <thead>
      <tr>
        <th class="zone_id">Zone ID</th>
        <th class="name">区域名称</th>
        <th class="operation hack">操作</th>
      </tr>
    </thead>
    <tbody>
      <?php
if (empty($D['data'])) {
    echo '<tr><td colspan="10">尚未添加任何区域</td></tr>';
} else {
    foreach($D['data'] as $row) {
        echo <<<EOT
        <tr>
          <td>{$row['zone_id']}</td>
          <td>{$row['name']}</td>
          <td><a href="../gateway.php?service=ZoneDataService&action=showUpdate&id={$row['zone_id']}" ajaxTarget="#ajaxContainer">修改</a></td>
        </tr>
EOT;
    }
}
  ?>
    </tbody>
  </table>
  <div class="gPageList">
    <div class="stat">当前 <?php echo $D['page'];?>/<?php echo $D['totalPage'];?> 页 每页 <?php echo $D['pageSize'];?> 条 总共 <?php echo $D['count'];?> 条</div>
    <div class="list">
<?php
for ($i = 1; $i <= $D['totalPage']; ++$i) {
    $str = ($i == $D['page']) ? ' class="cur"' : '';
    echo '<a' . $str . ' href="../gateway.php?service=ZoneDataService&action=showList&orderColumn=' . $D['orderColumn'] . '&orderMethod=' . $D['orderMethod'] . '&page=' . $i . '" ajaxTarget="#ajaxContainer">' . $i . '</a>';
}
?>
    </div>
  </div>
</div>
<script type="text/javascript">
$(function(){
	var urlPrefix = '../gateway.php?service=ZoneDataService&action=showList';
	var orderColumn = '<?php echo $D['orderColumn']; ?>';
	var orderMethod = '<?php echo $D['orderMethod']; ?>';
	$.applyTableSorter(urlPrefix, orderColumn, orderMethod);
});
</script>
