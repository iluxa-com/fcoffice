<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;系统相关链接&nbsp;&gt;&gt;&nbsp;调试日志</div>
<div class="gMain">
  <fieldset style="margin-bottom:6px; text-indent:6px;">
    <form class="gFilterFrm ajaxSubmit" action="../gateway.php" method="get" ajaxTarget="#ajaxContainer">
      <input type="hidden" name="service" value="AdminService" />
      <input type="hidden" name="action" value="showDebugLog" />
      <label for="log_type">日志类型</label>
      <select id="log_type" name="filter[log_type]">
        <option value="">－所有类型－</option>
        <?php
        foreach ($D['data1'] as $row) {
            $logTypeStr = sprintf('%05d', $row['log_type']);
            if ($D['filter']['log_type'] == $row['log_type']) {
                $str = ' selected="selected"';
            } else {
                $str = '';
            }
            echo <<<EOT
<option value="{$row['log_type']}"{$str}>{$logTypeStr}({$row['total']}条)</option>
EOT;
        }
        ?>
      </select>
      <label for="user_id">用户ID:</label>
      <input type="text" id="user_id" name="filter[user_id]" value="<?php echo $D['filter']['user_id']; ?>" />
      <label for="date">创建日期:</label>
      <input class="datepicker" type="input" id="date" name="filter[date]" value="<?php echo $D['filter']['date']; ?>" />
      <button type="submit">筛选</button>
      <button type="button" id="showAllBtn">查看全部</button>
    </form>
  </fieldset>
  <table class="gTable gHover tableSorter logList" style="margin-top:6px;" width="100%" border="0" cellpadding="0" cellspacing="1">
    <thead>
      <tr>
        <th class="log_type">日志类型</th>
        <th class="user_id">用户ID</th>
        <th class="log_data hack">日志数据</th>
        <th class="create_time">创建时间</th>
      </tr>
    </thead>
    <tbody>
      <?php
if (empty($D['data2'])) {
    echo '<tr><td colspan="12">尚未任何日志</td></tr>';
} else {
    $reflectionClass = new ReflectionClass('UserException');
    $constArr = $reflectionClass->getConstants();
    $constArr = array_flip($constArr);
    foreach($D['data2'] as $row) {
        $createTimeStr = date('Y-m-d H:i:s', $row['create_time']);
        $logTypeDesc = isset($constArr[$row['log_type'] % 10000]) ? $constArr[$row['log_type'] % 10000] : 'ERROR_UNDEFINED';
        echo <<<EOT
      <tr>
        <td style="line-height:120%;"><div>{$row['log_type']}</div><div>{$logTypeDesc}</div></td>
        <td>{$row['user_id']}</td>
        <td><textarea ondblclick="this.select();" rows="2" cols="50" style="width:99%; margin:0 auto;">{$row['log_data']}</textarea></td>
        <td>{$createTimeStr}</td>
      </tr>
EOT;
    }
}
$filterStr = http_build_query(array('filter' => $D['filter']));
?>
    </tbody>
  </table>
  <div class="gPageList">
    <div class="stat">当前 <?php echo $D['page'];?>/<?php echo $D['totalPage'];?> 页 每页 <?php echo $D['pageSize'];?> 条 总共 <?php echo $D['count'];?> 条</div>
    <div class="list">
      <?php
      $urlPrefix = '../gateway.php?service=AdminService&action=showDebugLog&' . $filterStr . '&orderColumn=' . $D['orderColumn'] . '&orderMethod=' . $D['orderMethod'] . '&page=';
      echo Pager::getPageList($urlPrefix, $D['page'], $D['totalPage'], 5, 'cur', ' ajaxTarget="#ajaxContainer"');
?>
    </div>
  </div>
  <ol>
    <li>１、类型29999是唯一的一个特殊类型，表示服务返回代码为-1(29999=30000+(-1)，属于30XXX类型)；</li>
    <li>２、类型10XXX(首页异常)，20XXX(服务异常)，30XXX(服务失败)，40XXX(运行时错误)中XXX的具体含义请参照相关对照表；</li>
    <li>３、非０的用户ID表示登录用户的ID；</li>
  </ol>
</div>
<script type="text/javascript">
$(function(){
	var urlPrefix = '../gateway.php?service=AdminService&action=showDebugLog&<?php echo $filterStr; ?>';
	var orderColumn = '<?php echo $D['orderColumn']; ?>';
	var orderMethod = '<?php echo $D['orderMethod']; ?>';
	$.applyTableSorter(urlPrefix, orderColumn, orderMethod);

	$('#showAllBtn').click(function() {
		$('#log_type, #user_id, #date').val('');
		$(this).closest('form').submit();
	});
});
</script>