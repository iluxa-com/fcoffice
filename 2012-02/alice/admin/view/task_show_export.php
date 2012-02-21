<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;任务数据管理&nbsp;&gt;&gt;&nbsp;导出数据</div>
<div class="gMain">
  <form class="gFrm ajaxSubmit" action="../gateway.php?service=TaskDataService&action=doExport" method="post">
    <table class="gTable" width="100%" border="0" cellpadding="0" cellspacing="1">
      <tr>
        <td class="td1">导出类型:</td>
        <td class="td2" style="text-align:left;padding-left:6px;"><select name="type">
            <option value="data">数据</option>
            <option value="index">索引</option>
          </select></td>
        <td class="td3">说明：数据/索引。</td>
      </tr>
      <tr>
        <td class="td1">导出格式：</td>
        <td class="td2" style="text-align:left;padding-left:6px;"><select name="format">
            <!--<option value="csv">CSV文件</option>-->
            <!--<option value="xml">XML文件</option>-->
            <option value="json" selected="selected">JSON文件</option>
            <!--<option value="php">PHP文件</option>-->
          </select></td>
        <td class="td3">说明：请选择导出文件格式。</td>
      </tr>
      <tr>
        <td class="td1">是否压缩：</td>
        <td class="td2" style="text-align:left;padding-left:6px;"><select name="compress">
            <option value="0">不压缩</option>
            <!--<option value="1">压缩</option>-->
          </select></td>
        <td class="td3">说明：是否使用gzip压缩。</td>
      </tr>
    </table>
    <table class="gSubmit" width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="td1">&nbsp;</td>
        <td class="td2"><button type="submit">导出</button>
          <button class="gGoBack" type="button">返回</button></td>
        <td class="td3">&nbsp;</td>
      </tr>
    </table>
  </form>
</div>
