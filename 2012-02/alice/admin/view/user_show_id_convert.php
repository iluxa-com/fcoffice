<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;用户数据管理&nbsp;&gt;&gt;&nbsp;ＩＤ转换</div>
<div class="gMain">
  <form id="idConvertFrm" class="ajaxSubmit" action="../gateway.php?service=UserDataService&action=doIdConvert" method="post">
    user_id
    <input type="text" name="user_id" style="width:230px;" />
    <button type="submit">&lt;&lt;转换&gt;&gt;</button>
    <input type="text" name="sns_uid" style="width:230px;" />
    sns_uid
  </form>
  <ol id="historyList"></ol>
</div>
<script type="text/javascript">
function ajaxCallback(data) {
	$('#historyList').append('<li>' + data.user_id + ' => ' + data.sns_uid + '</li>');
	$('#idConvertFrm input').val('');
}
$(function(){
	$('#idConvertFrm').data('validateCallback',function(){
		var userId = $('#idConvertFrm input[name=user_id]').val();
		var snsUid = $('#idConvertFrm input[name=sns_uid]').val();
		if(userId == '' && snsUid == '') {
			alert('user_id和sns_uid必须选填一项');
			return false;
		} else if (userId !='' && snsUid !='') {
			alert('user_id和sns_uid只能选填一项');
			return false;
		}
		return true;
	});
});
</script>