<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;数据统计图表&nbsp;&gt;&gt;&nbsp;详细数据</div>
<div class="gMain">
  <table class="gTable gHover statReport" width="100%" border="0" cellpadding="0" cellspacing="1">
    <thead>
      <tr>
        <th class="date">日期</th>
        <th class="total">总用户</th>
        <th class="active">活跃</th>
        <th class="new">新增</th>
        <th class="old">留存</th>
        <th class="back_user1">一天回头</th>
        <th class="back_user3">三天回头</th>
        <th class="back_user7">七天回头</th>
        <th class="invite_send">邀请发送</th>
        <th class="invite_accept">邀请接受</th>
        <th class="pay_num">充值人数</th>
        <th class="pay_in">充值金额</th>
        <th class="pay_first">首次充值</th>
        <th class="active_rate">活跃率</th>
        <th class="old_rate">留存率</th>
      </tr>
    </thead>
    <tbody>
      <?php
if (empty($D['data'])) {
    echo '<tr><td colspan="15">无任何统计数据</td></tr>';
} else {
    foreach($D['data'] as $row) {
        echo <<<EOT
        <tr>
          <td>{$row['date']}</td>
          <td>{$row['total']}</td>
          <td>{$row['active']}</td>
          <td>{$row['new']}</td>
          <td>{$row['old']}</td>
          <td>{$row['back_user1']}</td>
          <td>{$row['back_user3']}</td>
          <td>{$row['back_user7']}</td>
          <td>{$row['invite_send']}</td>
          <td>{$row['invite_accept']}</td>
          <td>{$row['pay_num']}</td>
          <td>{$row['pay_in']}</td>
          <td>{$row['pay_first']}</td>
          <td>{$row['active_rate']}</td>
          <td>{$row['old_rate']}</td>
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
    echo '<a' . $str . ' href="../gateway.php?service=StatService&action=showDetailStat&page=' . $i.'" ajaxTarget="#ajaxContainer">' . $i . '</a>';
}
?>
    </div>
  </div>
  <dl style="margin-top:6px;">
    <dt><b>名词解释：</b></dt>
    <dd>一、总用户：截止当日，安装应用的总用户数；</dd>
    <dd>二、活跃：当日有登录游戏的用户数；</dd>
    <dd>三、新增：新增用户=当日总用户-昨日总用户；</dd>
    <dd>四、留存：留存用户=当日活跃用户-当日新增用户；</dd>
    <dd>五、活跃率=当日活跃用户/当日总用户；</dd>
    <dd>六、留存率=当日留存用户/昨日活跃用户；</dd>
    <dd>七、邀请发送：当日所有用户发送的邀请数(由于技术原因，此数值可能存在误差，仅供参数)；</dd>
    <dd>八、邀请接受：当日所有接受邀请数(由于技术原因，此数值可能存在误差，仅供参数)；</dd>
    <dd>九、充值人数：当日充值用户的总人数，同一用户只计一次；</dd>
    <dd>十、充值总额：当日所有用户成功充值的金额和；</dd>
  </dl>
</div>
