<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;任务数据管理&nbsp;&gt;&gt;&nbsp;修改任务</div>
<div class="gMain">
  <form class="gFrm ajaxSubmit" action="../gateway.php?service=TaskDataService&action=updateTaskData&id=<?php echo $D['data']['task_id'];?>" method="post">
    <table class="gTable" width="100%" border="0" cellpadding="0" cellspacing="1">
      <tr>
        <td class="td1">Task ID：</td>
        <td class="td2"><input type="text" name="task_id" value="<?php echo $D['data']['task_id'];?>" disabled="disabled" /></td>
        <td class="td3">说明：任务ID，必须唯一。</td>
      </tr>
      <tr>
        <td class="td1">Zone ID：</td>
        <td class="td2"><input type="text" name="zone_id" value="<?php echo $D['data']['zone_id'];?>" /></td>
        <td class="td3">说明：区域ID。</td>
      </tr>
      <tr>
        <td class="td1">Place ID：</td>
        <td class="td2"><input type="text" name="place_id" value="<?php echo $D['data']['place_id'];?>" /></td>
        <td class="td3">说明：地区ID。</td>
      </tr>
      <tr>
        <td class="td1">NPC ID：</td>
        <td class="td2"><input type="text" name="npc_id" value="<?php echo $D['data']['npc_id'];?>" /></td>
        <td class="td3">说明：NPC ID。</td>
      </tr>
      <tr>
        <td class="td1">任务类型：</td>
        <td class="td2"><input type="text" name="type" value="<?php echo $D['data']['type'];?>" /></td>
        <td class="td3">说明：0=主线任务，1=系统任务，2=NPC任务，3=新手任务。</td>
      </tr>
      <tr>
        <td class="td1">Grade：</td>
        <td class="td2"><input type="text" name="grade" value="<?php echo $D['data']['grade'];?>" /></td>
        <td class="td3">说明：任务需要等级。</td>
      </tr>
      <tr>
        <td class="td1">任务名称：</td>
        <td class="td2"><input type="text" name="name" value="<?php echo $D['data']['name'];?>" /></td>
        <td class="td3">说明：任务名称。</td>
      </tr>
      <tr>
        <td class="td1">任务需求：</td>
        <td class="td2"><textarea name="need" cols="40" rows="6" readonly="readonly" class="jsonMaker hack"><?php echo $D['data']['need'];?></textarea></td>
        <td class="td3">说明：json编码格式，如：{"items":[{"id":1,"num":1},{"id":2,"num":5}]}。</td>
      </tr>
      <tr>
        <td class="td1">任务奖励：</td>
        <td class="td2"><textarea name="reward" cols="40" rows="6" readonly="readonly" class="jsonMaker"><?php echo $D['data']['reward'];?></textarea></td>
        <td class="td3">说明：json编码格式，如：{"silver":2500,"items":["id":2,"num":1]}。</td>
      </tr>
      <tr>
        <td class="td1">任务描述1：</td>
        <td class="td2"><textarea name="description1" cols="40" rows="6"><?php echo $D['data']['description1'];?></textarea></td>
        <td class="td3">说明：长度不超过1000个字。</td>
      </tr>
      <tr>
        <td class="td1">任务描述2：</td>
        <td class="td2"><textarea name="description2" cols="40" rows="6"><?php echo $D['data']['description2'];?></textarea></td>
        <td class="td3">说明：长度不超过1000个字。</td>
      </tr>
      <tr>
        <td class="td1">任务描述3：</td>
        <td class="td2"><textarea name="description3" cols="40" rows="6"><?php echo $D['data']['description3'];?></textarea></td>
        <td class="td3">说明：长度不超过1000个字。</td>
      </tr>
    </table>
    <table class="gSubmit" width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="td1">&nbsp;</td>
        <td class="td2"><button type="submit">修改</button>
          <button class="gGoBack" type="button">返回</button></td>
        <td class="td3">&nbsp;</td>
      </tr>
    </table>
  </form>
</div>
