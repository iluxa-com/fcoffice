<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;数据统计图表&nbsp;&gt;&gt;&nbsp;用户统计</div>
<div class="gMain">
  <fieldset style="margin-bottom:6px; text-indent:6px;">
  <form class="gFilterFrm ajaxSubmit" action="../gateway.php" method="get" ajaxTarget="#ajaxContainer">
    <input type="hidden" name="service" value="StatService" />
    <input type="hidden" name="action" value="showUserStat" />
    <label for="type">类型：</label>
    <select id="type" name="type">
      <option value="hour_active"<?php if($D['type']=='hour_active') echo ' selected="selected"'; ?>>时/天（在线人数）</option>
      <option value="hour_total"<?php if($D['type']=='hour_total') echo ' selected="selected"'; ?>>时/天（总用户数）</option>
      <option value="day_active"<?php if($D['type']=='day_active') echo ' selected="selected"'; ?>>天/月（在线人数）</option>
      <option value="day_total"<?php if($D['type']=='day_total') echo ' selected="selected"'; ?>>天/月（总用户数）</option>
      <option value="month_active"<?php if($D['type']=='month_active') echo ' selected="selected"'; ?>>月/年（在线人数）</option>
      <option value="month_total"<?php if($D['type']=='month_total') echo ' selected="selected"'; ?>>月/年（总用户数）</option>
    </select>
    <label for="date1">日期1：</label>
    <input class="datepicker" type="text" id="date1" name="date1" value="<?php echo $D['date1']; ?>" readonly="readonly" />
    <select name="compare">
      <option value="1"<?php if($D['compare']=='1') echo ' selected="selected"'; ?>>对比</option>
      <option value="0"<?php if($D['compare']=='0') echo ' selected="selected"'; ?>>不对比</option>
    </select>
    <label for="date2">日期2：</label>
    <input class="datepicker" type="text" id="date2" name="date2" value="<?php echo $D['date2']; ?>" readonly="readonly" />
    <button type="submit">查看</button>
  </form>
  </fieldset>
  <div id="flashDiv">loading...</div>
</div>
<?php require_once 'open_flash_chart.php'; ?>
