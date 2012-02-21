<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;地点数据管理&nbsp;&gt;&gt;&nbsp;地点列表</div>
<div class="gMain">
  <table class="gTable gHover tableSorter placeList" width="100%" border="0" cellpadding="0" cellspacing="1">
    <thead>
      <tr>
        <th class="zone_id">Zone ID</th>
        <th class="place_id">Place ID</th>
        <th class="name">地点名称</th>
        <th class="need_grade">等级限制</th>
        <th class="type">类型(0=镇城,1=闯关)</th>
        <th class="operation hack">操作</th>
      </tr>
    </thead>
    <tbody>
<?php
if (empty($D['data'])) {
    echo '<tr><td colspan="6">尚未添加任何地点</td></tr>';
} else {
    foreach($D['data'] as $row) {
        echo <<<EOT
        <tr>
          <td>{$row['zone_id']}</td>
          <td>{$row['place_id']}</td>
          <td>{$row['name']}</td>
          <td>{$row['need_grade']}</td>
          <td>{$row['type']}</td>
          <td><a href="../gateway.php?service=PlaceDataService&action=showUpdate&id={$row['place_id']}" ajaxTarget="#ajaxContainer">修改</a></td>
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
for ($i = 1;$i <= $D['totalPage']; ++$i) {
    $str = ($i == $D['page']) ? ' class="cur"' : '';
    echo '<a' . $str . ' href="../gateway.php?service=PlaceDataService&action=showList&orderColumn=' . $D['orderColumn'] . '&orderMethod=' . $D['orderMethod'] . '&page=' . $i . '" ajaxTarget="#ajaxContainer">' . $i . '</a>';
}
?>
    </div>
  </div>
</div>
<script type="text/javascript">
$(function(){
	var urlPrefix = '../gateway.php?service=PlaceDataService&action=showList';
	var orderColumn = '<?php echo $D['orderColumn']; ?>';
	var orderMethod = '<?php echo $D['orderMethod']; ?>';
	$.applyTableSorter(urlPrefix, orderColumn, orderMethod);
});
</script>
