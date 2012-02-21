<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;任务数据管理&nbsp;&gt;&gt;&nbsp;添加任务</div>
<div class="gMain">
  <form class="gFrm ajaxSubmit" action="../gateway.php?service=TaskDataService&action=addTaskData" method="post">
    <table class="gTable" width="100%" border="0" cellpadding="0" cellspacing="1">
      <tr>
        <td class="td1">Task ID：</td>
        <td class="td2"><input type="text" name="task_id" class="positiveIntegerFilter" /></td>
        <td class="td3">说明：任务ID，必须唯一。</td>
      </tr>
      <tr>
        <td class="td1">Zone ID：</td>
        <td class="td2"><input type="text" name="zone_id" class="positiveIntegerFilter" /></td>
        <td class="td3">说明：区域ID。</td>
      </tr>
      <tr>
        <td class="td1">Place ID：</td>
        <td class="td2"><input type="text" name="place_id" class="positiveIntegerFilter" /></td>
        <td class="td3">说明：地区ID。</td>
      </tr>
      <tr>
        <td class="td1">NPC ID：</td>
        <td class="td2"><input type="text" name="npc_id" class="positiveIntegerFilter" /></td>
        <td class="td3">说明：NPC ID。</td>
      </tr>
      <tr>
        <td class="td1">任务类型：</td>
        <td class="td2"><input type="text" name="type" class="positiveIntegerFilter" /></td>
        <td class="td3">说明：0=主线任务，1=系统任务，2=NPC任务，3=新手任务。</td>
      </tr>
      <tr>
        <td class="td1">Grade：</td>
        <td class="td2"><input type="text" name="grade" class="positiveIntegerFilter" /></td>
        <td class="td3">说明：任务需要等级。</td>
      </tr>
      <tr>
        <td class="td1">任务名称：</td>
        <td class="td2"><input type="text" name="name" /></td>
        <td class="td3">说明：任务名称。</td>
      </tr>
      <tr>
        <td class="td1">任务需求：</td>
        <td class="td2"><textarea name="need" cols="40" rows="6" readonly="readonly" class="jsonMaker hack"></textarea></td>
        <td class="td3">说明：json编码格式，如：{"items":[{"id":1,"num":1},{"id":2,"num":5}]}。</td>
      </tr>
      <tr>
        <td class="td1">任务奖励：</td>
        <td class="td2"><textarea name="reward" cols="40" rows="6" readonly="readonly" class="jsonMaker"></textarea></td>
        <td class="td3">说明：json编码格式，如：{"silver":2500,"items":["id":2,"num":1]}。</td>
      </tr>
      <tr>
        <td class="td1">任务描述1：</td>
        <td class="td2"><textarea name="description1" cols="40" rows="6"></textarea></td>
        <td class="td3">说明：长度不超过1000个字。</td>
      </tr>
      <tr>
        <td class="td1">任务描述2：</td>
        <td class="td2"><textarea name="description1" cols="40" rows="6"></textarea></td>
        <td class="td3">说明：长度不超过1000个字。</td>
      </tr>
      <tr>
        <td class="td1">任务描述3：</td>
        <td class="td2"><textarea name="description3" cols="40" rows="6"></textarea></td>
        <td class="td3">说明：长度不超过1000个字。</td>
      </tr>
    </table>
    <table class="gSubmit" width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="td1">&nbsp;</td>
        <td class="td2"><button type="submit">添加</button>
          <button class="gGoBack" type="button">返回</button></td>
        <td class="td3">&nbsp;</td>
      </tr>
    </table>
  </form>
</div>
