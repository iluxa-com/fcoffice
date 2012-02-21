<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;后台用户管理&nbsp;&gt;&gt;&nbsp;用户列表</div>
<div class="gMain">
  <table class="gTable gHover tableSorter adminUserList" width="100%" border="0" cellpadding="0" cellspacing="1">
    <thead>
      <tr>
        <th class="email">E-mail</th>
        <th class="name">姓名</th>
        <th class="roles">角色</th>
        <th class="note">备注</th>
        <th class="last_login">最后登录时间</th>
        <th class="last_ip">最后登录IP</th>
        <th class="login_times">登录次数</th>
        <th class="create_time">创建时间</th>
        <th class="operation hack">操作</th>
      </tr>
    </thead>
    <tbody>
<?php
if (empty($D['data'])) {
    echo '<tr><td colspan="9">尚未添加任何帐号</td></tr>';
} else {
    foreach ($D['data'] as $row) {
        $lastLogin = date('Y-m-d H:i', $row['last_login']);
        $createTime = date('Y-m-d', $row['create_time']);
        echo <<<EOT
      <tr>
        <td>{$row['email']}</td>
        <td>{$row['name']}</td>
        <td>{$row['roles']}</td>
        <td>{$row['note']}</td>
        <td>{$lastLogin}</td>
        <td>{$row['last_ip']}</td>
        <td>{$row['login_times']}</td>
        <td>{$createTime}</td>
        <td><a href="../gateway.php?service=AdminUserService&action=showUpdate&email={$row['email']}" ajaxTarget="#ajaxContainer">修改</a>&nbsp;|&nbsp;<a class="ajaxDelLink" href="../gateway.php?service=AdminUserService&action=doDelete&email={$row['email']}">删除</a></td>
      </tr>
EOT;
    }
}
?>
    </tbody>
  </table>
  <div class="gPageList">
    <div class="stat">当前 <?php echo $D['page']; ?>/<?php echo $D['totalPage']; ?> 页 每页 <?php echo $D['pageSize']; ?> 条 总共 <?php echo $D['count']; ?> 条</div>
    <div class="list">
<?php
for ($i = 1; $i <= $D['totalPage']; ++$i) {
    $str = ($i == $D['page']) ? ' class="cur"' : '';
    echo '<a' . $str . ' href="../gateway.php?service=AdminUserService&action=showList&orderColumn=' . $D['orderColumn'] . '&orderMethod=' . $D['orderMethod'] . '&page=' . $i . '" ajaxTarget="#ajaxContainer">' . $i . '</a>';
}
?>
    </div>
  </div>
</div>
<script type="text/javascript">
$(function(){
	var urlPrefix = '../gateway.php?service=AdminUserService&action=showList';
	var orderColumn = '<?php echo $D['orderColumn']; ?>';
	var orderMethod = '<?php echo $D['orderMethod']; ?>';
	$.applyTableSorter(urlPrefix, orderColumn, orderMethod);
});
</script>
