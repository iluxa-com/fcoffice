<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;用户数据管理&nbsp;&gt;&gt;&nbsp;数据下载</div>
<div class="gMain">
  <form class="gFrm" action="../gateway.php" method="get">
    <input type="hidden" name="service" value="StatService" />
    <input type="hidden" name="action" value="doDownload" />
    <table class="gTable" width="100%" border="0" cellpadding="0" cellspacing="1">
      <tr>
        <td class="td1">下载类型：</td>
        <td class="td2" style="text-align:left;padding-left:6px;"><select name="type">
            <option value="">－请选择－</option>
            <option value="user_hour">用户统计（时/天）</option>
            <option value="user_day">用户统计（天/月）</option>
            <option value="user_month">用户统计（月/年）</option>
            <option value="grade">等级分布</option>
            <option value="step">新手流失</option>
          </select>
        <td class="td3">说明：请选择下载类型。</td>
      </tr>
    </table>
    <table class="gSubmit" width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="td1">&nbsp;</td>
        <td class="td2"><button type="submit">下载</button>
          <button class="gGoBack" type="button">返回</button></td>
        <td class="td3">&nbsp;</td>
      </tr>
    </table>
  </form>
</div>
