<style type="text/css">
form {
	text-align:center;
}
form textarea {
	width:98%;
}
form button {
	width:100px;
}
</style>
<script type="text/javascript">
function ajaxCallback(data) {
	document.getElementById('output1').value = data.output1;
    document.getElementById('output2').value = data.output2;
    document.getElementById('output3').value = data.output3;
}
function emptyAll() {
    document.getElementById('input').value = '';
    document.getElementById('output1').value = '';
    document.getElementById('output2').value = '';
    document.getElementById('output3').value = '';
}
</script>
<div class="gNav">后台管理系统&nbsp;&gt;&gt;&nbsp;道具数据管理&nbsp;&gt;&gt;&nbsp;道具ID查询</div>
<div class="gMain">
  <form class="gFrm ajaxSubmit" action="../gateway.php?service=ItemDataService&action=doSearch" method="post">
    <div>
      <label for="input">输入区：</label>
    </div>
    <div>
      <textarea id="input" name="input" cols="60" rows="10"></textarea>
    </div>
    <div>
      <button type="submit">查询</button>
      <button type="button" onclick="emptyAll();">清空</button>
    </div>
    <div>
      <label for="output">输出区：</label>
    </div>
    <div>
      <textarea id="output1" cols="60" rows="10"></textarea>
      <textarea id="output2" cols="60" rows="10"></textarea>
      <textarea id="output3" cols="60" rows="4"></textarea>
    </div>
  </form>
</div>
