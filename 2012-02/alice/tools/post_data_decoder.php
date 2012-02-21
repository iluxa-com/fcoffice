<?php
if (!empty($_POST['input'])) {
	$input = $_POST['input'];
	$str = urldecode($input);
	parse_str($str, $arr);
	$output = var_export($arr, true);
} else {
	$input = '';
	$output = '';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Post Data Decoder</title>
<style type="text/css">
body {
	width:960px;
	margin:0 auto;
	font-size:14px;
	font-weight:bold;
}
form {
	text-align:center;
}
form textarea {
	width:100%;
}
form button {
	width:100px;
}
</style>
<script type="text/javascript">
function emptyAll() {
	var i = document.getElementById('input');
	var o = document.getElementById('output');
	i.value = '';
	o.value = '';
}
</script>
</head>
<body>
<form action="" method="post">
  <div>
    <label for="input">输入区：</label>
  </div>
  <div>
    <textarea id="input" name="input" cols="60" rows="10"><?php echo $input; ?></textarea>
  </div>
  <div>
    <button type="submit">解码</button>
    <button type="button" onclick="emptyAll();">清空</button>
  </div>
  <div>
    <label for="output">输出区：</label>
  </div>
  <div>
    <textarea id="output" name="output" cols="60" rows="15"><?php echo $output; ?></textarea>
  </div>
</form>
</body>
</html>
