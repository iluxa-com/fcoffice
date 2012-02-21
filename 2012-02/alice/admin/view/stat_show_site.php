<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;数据统计图表&nbsp;&gt;&gt;&nbsp;站点统计</div>
<div class="gMain">
  <table class="gTable gHover statReport" width="100%" border="0" cellpadding="0" cellspacing="1">
    <thead>
      <tr>
        <th class="platform">游戏平台</th>
        <th class="url">游戏地址</th>
        <th class="operation">操作</th>
      </tr>
    </thead>
    <tbody>
      <?php
if (empty($D['data'])) {
    echo '<tr><td colspan="3">无任何站点</td></tr>';
} else {
    foreach($D['data'] as $row) {
        echo <<<EOT
        <tr>
          <td>{$row['name']}</td>
          <td><a href="{$row['url']}" target="_blank">{$row['url']}</a></td>
          <td><a href="http://new.cnzz.com/v1/login.php?siteid={$row['siteid']}" target="_blank">查看统计数据</a></td>
        </tr>
EOT;
      }
}
  ?>
    </tbody>
  </table>
</div>
