<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;数据统计图表&nbsp;&gt;&gt;&nbsp;闯关统计</div>
<div class="gMain">
  <fieldset style="margin-bottom:6px; text-indent:6px;">
  <form class="gFilterFrm ajaxSubmit" action="../gateway.php" method="get" ajaxTarget="#ajaxContainer">
    <input type="hidden" name="service" value="<?php echo $_GET['service']; ?>" />
    <input type="hidden" name="action" value="<?php echo $_GET['action']; ?>" />
    <label for="start">Start:</label>
    <input class="datepicker" type="input" id="start" name="start" value="<?php echo $D['start']; ?>" readonly="readonly" />
    <label for="end">End:</label>
    <input class="datepicker" type="input" id="end" name="end" value="<?php echo $D['end']; ?>" readonly="readonly" />
    <button type="submit">查看</button>
  </form>
  </fieldset>
  <table class="gTable gHover statReport" width="100%" border="0" cellpadding="0" cellspacing="1">
    <thead>
      <tr>
        <th class="node">闯关情况</th>
<?php
foreach ($D['column'] as $column) {
    echo <<<EOT
        <th class="day">{$column}</th>
EOT;
}
?>
      </tr>
    </thead>
    <tbody>
<?php
if (empty($D['data'])) {
    echo '<tr><td colspan="11">无任何统计数据</td></tr>';
} else {
    foreach ($D['data'] as $step => $row) {
        echo <<<EOT
      <tr>
        <td>{$step}</td>
EOT;
       foreach ($D['column'] as $column) {
           echo <<<EOT
        <td>{$row[$column]}</td>
EOT;
       }
       echo <<<EOT
      </tr>
EOT;
    }
}
?>
    </tbody>
  </table>
</div>
