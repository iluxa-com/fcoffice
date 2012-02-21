<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;数据统计图表&nbsp;&gt;&gt;&nbsp;其他数据</div>
<div class="gMain">
  <fieldset style="margin-bottom:6px; text-indent:6px;">
  <form class="gFilterFrm ajaxSubmit" action="../gateway.php" method="get" ajaxTarget="#ajaxContainer">
    <input type="hidden" name="service" value="StatService" />
    <input type="hidden" name="action" value="showOtherStat" />
    <label for="date">Date:</label>
    <input class="datepicker" type="input" id="date" name="date" value="<?php echo $D['date'];?>" readonly="readonly" />
    <select name="type">
      <option value="102"<?php if($D['type']==102) echo ' selected="selected"';?>>体力使用量</option>
      <option value="100"<?php if($D['type']==100) echo ' selected="selected"';?>>购买道具数</option>
      <option value="103"<?php if($D['type']==103) echo ' selected="selected"';?>>完成任务数</option>
    </select>
    <button type="submit">查看</button>
  </form>
  </fieldset>
  <div id="flashDiv">loading...</div>
</div>
<?php require_once 'open_flash_chart.php'; ?>
