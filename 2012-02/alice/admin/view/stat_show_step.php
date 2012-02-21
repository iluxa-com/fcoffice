<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;数据统计图表&nbsp;&gt;&gt;&nbsp;新手流失</div>
<div class="gMain">
  <table class="gTable gHover stepStat" width="100%" border="0" cellpadding="0" cellspacing="1">
    <thead>
      <tr>
        <th class="date">日期</th>
        <th class="s1">s1</th>
        <th class="s2">s2</th>
        <th class="s3">s3</th>
        <th class="s4">s4</th>
        <th class="s5">s5</th>
        <th class="s6">s6</th>
        <th class="s7">s7</th>
        <th class="s8">s8</th>
        <th class="s9">s9</th>
        <th class="s10">s10</th>
        <th class="s11">s11</th>
        <th class="s12">s12</th>
        <th class="s13">s13</th>
        <th class="s14">s14</th>
        <th class="s15">s15</th>
        <th class="s16">s16</th>
        <th class="s17">s17</th>
        <th class="s18">s18</th>
        <th class="s19">s19</th>
      </tr>
    </thead>
    <tbody>
<?php
if (empty($D['data'])) {
    echo '<tr><td colspan="20">无任何统计数据</td></tr>';
} else {
    foreach ($D['data'] as $row) {
        echo <<<EOT
      <tr>
        <td>{$row['date']}</td>
        <td>{$row['s1']}</td>
        <td>{$row['s2']}</td>
        <td>{$row['s3']}</td>
        <td>{$row['s4']}</td>
        <td>{$row['s5']}</td>
        <td>{$row['s6']}</td>
        <td>{$row['s7']}</td>
        <td>{$row['s8']}</td>
        <td>{$row['s9']}</td>
        <td>{$row['s10']}</td>
        <td>{$row['s11']}</td>
        <td>{$row['s12']}</td>
        <td>{$row['s13']}</td>
        <td>{$row['s14']}</td>
        <td>{$row['s15']}</td>
        <td>{$row['s16']}</td>
        <td>{$row['s17']}</td>
        <td>{$row['s18']}</td>
        <td>{$row['s19']}</td>
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
    echo '<a' . $str . ' href="../gateway.php?service=StatService&action=showStepStat&page=' . $i.'" ajaxTarget="#ajaxContainer">' . $i . '</a>';
}
?>
    </div>
  </div>
</div>
<style type="text/css">
#descTab {
	background:#ddd;
	width:350px;
	margin-left:6px;
}
#descTab tr {
	height:24px;
	line-height:24px;
}
#descTab td {
	background:#fff;
}
#descTab .td1 {
	width:90px;
	text-align:center;
}
#descTab .td2 {
	text-align:left;
	text-indent:6px;
}
</style>
<table id="descTab" cellspacing="1" cellpadding="0">
  <tr>
    <th colspan="2" align="center">《童话迷城》新手流失数据指标提取</th>
  </tr>
  <tr>
    <td class="td1">步骤</td>
    <td class="td2">操作</td>
  </tr>
  <tr>
    <td class="td1">第1步</td>
    <td class="td2">进入游戏加载</td>
  </tr>
  <tr>
    <td class="td1">第2步</td>
    <td class="td2">建立角色</td>
  </tr>
  <tr>
    <td class="td1">第3步</td>
    <td class="td2">完成界面介绍点击</td>
  </tr>
  <tr>
    <td class="td1">第4步</td>
    <td class="td2">进入第一个关卡加载</td>
  </tr>
  <tr>
    <td class="td1">第5步</td>
    <td class="td2">进入第一个关卡CG动画</td>
  </tr>
  <tr>
    <td class="td1">第6步</td>
    <td class="td2">进入第一个关卡界面教程</td>
  </tr>
  <tr>
    <td class="td1">第7步</td>
    <td class="td2">完成第一个关卡闯关</td>
  </tr>
  <tr>
    <td class="td1">第8步</td>
    <td class="td2">完成新手流程</td>
  </tr>
  <tr>
    <td class="td1">第9步</td>
    <td class="td2">完成第二个关卡闯关</td>
  </tr>
  <tr>
    <td class="td1">第10步</td>
    <td class="td2">完成图鉴系统指引</td>
  </tr>
</table>
