<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;任务数据管理&nbsp;&gt;&gt;&nbsp;任务列表</div>
<div class="gMain">
  <table class="gTable gHover tableSorter taskList" width="100%" border="0" cellpadding="0" cellspacing="1">
    <thead>
      <tr>
        <th class="task_id">Task ID</th>
        <th class="zone_id">Zone ID</th>
        <th class="place_id">Place ID</th>
        <th class="area_id">Area ID</th>
        <th class="npc_id">NPC ID</th>
        <th class="target">Target</th>
        <th class="type">任务类型</th>
        <th class="grade">Grade</th>
        <th class="name">任务名称</th>
        <th class="need">任务需求</th>
        <th class="reward">任务奖励</th>
        <th class="operation hack">操作</th>
      </tr>
    </thead>
    <tbody>
      <?php
if (empty($D['data'])) {
    echo '<tr><td colspan="12">尚未添加任何任务</td></tr>';
} else {
    foreach($D['data'] as $row) {
        echo <<<EOT
      <tr>
        <td>{$row['task_id']}</td>
        <td>{$row['zone_id']}</td>
        <td>{$row['place_id']}</td>
        <td>{$row['area_id']}</td>
        <td>{$row['npc_id']}</td>
        <td>{$row['target']}</td>
        <td>{$row['type']}</td>
        <td>{$row['grade']}</td>
        <td>{$row['name']}</td>
        <td>{$row['need']}</td>
        <td>{$row['reward']}</td>
        <td><a href="../gateway.php?service=TaskDataService&action=showUpdate&id={$row['task_id']}" ajaxTarget="#ajaxContainer">修改</a></td>
      </tr>
      <tr class="description1" style="display:none;">
        <td colspan="12" style="text-align:left;line-height:150%;padding:0 6px;">任务描述1：{$row['description1']}</td>
      </tr>
      <tr class="description2" style="display:none;">
        <td colspan="12" style="text-align:left;line-height:150%;padding:0 6px;">任务描述2：{$row['description2']}</td>
      </tr>
      <tr class="description3" style="display:none;">
        <td colspan="12" style="text-align:left;line-height:150%;padding:0 6px;">任务描述3：{$row['description3']}</td>
      </tr>
EOT;
    }
}
?>
    </tbody>
  </table>
  <div style="margin-top:6px;">
    <button onclick="$('tr.description1, tr.description2, tr.description3').toggle();">隐藏/显示任务描述</button>
  </div>
  <div class="gPageList">
    <div class="stat">当前 <?php echo $D['page'];?>/<?php echo $D['totalPage'];?> 页 每页 <?php echo $D['pageSize'];?> 条 总共 <?php echo $D['count'];?> 条</div>
    <div class="list">
      <?php
      $urlPrefix = '../gateway.php?service=TaskDataService&action=showList&orderColumn=' . $D['orderColumn'] . '&orderMethod=' . $D['orderMethod'] . '&page=';
      echo Pager::getPageList($urlPrefix, $D['page'], $D['totalPage'], 5, 'cur', ' ajaxTarget="#ajaxContainer"');
?>
    </div>
  </div>
</div>
<script type="text/javascript">
$(function(){
	var urlPrefix = '../gateway.php?service=TaskDataService&action=showList';
	var orderColumn = '<?php echo $D['orderColumn']; ?>';
	var orderMethod = '<?php echo $D['orderMethod']; ?>';
	$.applyTableSorter(urlPrefix, orderColumn, orderMethod);
});
</script>
