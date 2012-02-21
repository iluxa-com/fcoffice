<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;用户数据管理&nbsp;&gt;&gt;&nbsp;背包信息</div>
<div class="gMain">
  <div>User ID：<?php echo $D['user_id'];?></div>
  <form class="gFrm ajaxSubmit" style="margin-top:6px;" action="../gateway.php?service=UserDataService&action=doBagAction&user_id=<?php echo $D['user_id'];?>" method="post">
    <input type="hidden" name="case" />
    <table class="gTable gHover bagItemList" width="100%" border="0" cellpadding="0" cellspacing="1">
      <thead>
        <tr>
          <th class="checkbox"><input type="checkbox" /></th>
          <th class="item_id">Item ID</th>
          <th class="num">Num</th>
          <th class="operation">操作</th>
        </tr>
      </thead>
      <tbody>
        <?php
if(empty($D['itemArr'])) {
	echo '<tr><td colspan="4">尚未添加任何数据！</td></tr>';
} else {
	foreach($D['itemArr'] as $itemId => $num) {
		echo <<<EOT
        <tr id="item_{$itemId}">
          <td><input type="checkbox" name="item[{$itemId}]" value="{$num}" /></td>
          <td>{$itemId}</td>
          <td><input type="text" value="{$num}" maxlength="4" class="positiveIntegerFilter" /></td>
          <td><a id="update_link_{$itemId}" href="javascript:void(0);" onclick="updateItem(1, '#update_link_{$itemId}');">更新</a> | <a id="delete_link_{$itemId}" href="javascript:void(0);" onclick="deleteItem(1, '#delete_link_{$itemId}');">删除</a></td>
        </tr>
EOT;
	}
}
?>
      </tbody>
    </table>
    <div style="margin-top:6px;">
      <button id="btn_update_selected" onclick="updateItem(2, '#btn_update_selected');return false;">更新选中</button>
      <button id="btn_delete_selected" onclick="deleteItem(2, '#btn_delete_selected');return false;">删除选中</button>
      <button type="button" onclick="$('#ajaxContainer').load('../gateway.php?service=UserDataService&action=showBag&user_id=<?php echo $D['user_id']; ?>');">刷新</button>
      <button class="gGoBack" type="button">返回</button>
    </div>
  </form>
  <form class="ajaxSubmit" style="margin-top:6px;" action="../gateway.php?service=UserDataService&action=doBagAction&user_id=<?php echo $D['user_id'];?>" method="post">
    <input type="hidden" name="case" value="add" />
    Item ID：
    <input type="text" name="item_id" class="positiveIntegerFilter"  />
    Num：
    <input type="text" name="num" class="positiveIntegerFilter" />
    <button type="submit">添加</button>
  </form>
</div>
<script type="text/javascript">
$(function(){
	$('table thead input:first').click(function(){
		if($(this).attr('checked')) {
			$(this).closest('form').find('tbody :checkbox').attr('checked', 'checked');
		} else {
			$(this).closest('form').find('tbody :checkbox').removeAttr('checked');
		}
	});
	$('form:first').find('tr').each(function(){
		$(this).find(':checkbox:first').data('oldVal', $(this).find('input:last').val());
	});
});
/**
 * 更新Item
 * @param int type 类型(1=单个,2=多个)
 * @param object/string s
 */
function updateItem(type, s) {
	obj = $(s);
	switch(type) {
		case 1:
			var tr = $(obj).closest('tr');
			var checkbox = tr.find(':checkbox:first'); 
			var newVal = tr.find('input:last').val();
			if (checkbox.val() == newVal) {
				alert('未变更，无需更新！');
				return;
			}
			$(obj).closest('form').find(':checkbox').removeAttr('checked');
			checkbox.attr('checked', 'checked');
			checkbox.val(newVal);
			break;
		case 2:
			var count = 0;
			$(obj).closest('form').find('tr').each(function(){
				var checkbox = $(this).find(':checkbox:first'); 
				var newVal = $(this).find('input:last').val();
				if (checkbox.val() != newVal) {
					++count;
					checkbox.val(newVal);
				} else {
					checkbox.removeAttr('checked');
				}
			});
			if (count == 0) {
				alert('未变更，无需更新！');
				return;
			}
			break;
	}
	$('form:first').find('input[name=case]').val('update');
	$('form:first').trigger('submit');
}
/**
 * 删除Item
 * @param int type 类型(1=单个,2=多个)
 * @param object/string s
 */
function deleteItem(type, s) {
    obj = $(s);
	if(!window.confirm('确定要执行该操作吗？')) {
		return;
	}
	switch(type) {
		case 1:
			$(obj).closest('form').find(':checkbox').removeAttr('checked');
			$(obj).closest('tr').find(':checkbox:first').attr('checked', 'checked');
			break;
		case 2:
			break;
	}
	$('form:first').find('input[name=case]').val('delete');
	$('form:first').trigger('submit');
}
/**
 * Ajax回调函数
 */
function ajaxCallback(data) {
	switch(data['case']) {
		case 'update':
			for (var i=0;i<data.ids.length;++i) {
				var oldVal = $('tr#item_' + data.ids[i]).find(':checkbox:first').data('oldVal');
				$('tr#item_' + data.ids[i]).find('input:last').val(oldVal);
			}
			alert('操作完成！(失败数=' + data.ids.length + ')');
			break;
		case 'delete':
			for (var i=0;i<data.ids.length;++i) {
				$('tr#item_' + data.ids[i]).remove();
			}
			alert('操作完成！(成功数=' + data.ids.length + ')');
			break;
		case 'add':
			alert('操作成功，请刷新！');
			break;
	}
}
</script> 
