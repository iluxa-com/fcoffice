<?php
date_default_timezone_set('Asia/Shanghai');
$time = time();
$input = '';
$bjTime = '?';
$unixTimestamp = '?';
if (isset($_POST['input'])) {
    $input = $_POST['input'];
    if (is_numeric($input)) {
        $bjTime = date('Y-m-d H:i:s', $input);
        $unixTimestamp = $input;
    } else {
        $bjTime = $input;
        $unixTimestamp = strtotime($bjTime);
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Time Converter</title>
</head>
<body>
<form action="" method="post">
  <p>服务器时间：<?php echo date('Y-m-d H:i:s e', $time); ?>　Unix时间戳=<?php echo $time; ?></p>
  <p>　操作说明：输入北京时间或Unix时间戳，点击“Convert”按钮进行转换，其中北京时间请使用“2011-02-03 15:30:00”这样的格式。</p>
  <p>　输入数据：<input type="text" name="input" value="<?php echo $input; ?>" />&nbsp;<button type="submit">Convert</button></p>
  <p>　转换结果：北京时间=<font color="red"><b><?php echo $bjTime; ?></b></font> Unix时间戳=<font color="red"><b><?php echo $unixTimestamp; ?></b></font></p>
</form>
</body>
</html>
