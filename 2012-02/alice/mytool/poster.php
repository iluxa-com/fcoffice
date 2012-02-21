<?php
// 引入配置文件
require_once '../config.php';
/**
*        Debug
*        require_once(BASE_DIR .'/mytool/firephp/fb.php');
*        fb($this);
*/
// 这里要将服务路径添加到自动搜寻路径
set_include_path(get_include_path() . PATH_SEPARATOR . SERVICE_DIR);

// 扫描服务路径下的所有文件
$fileArr = scandir(SERVICE_DIR);

// 提取服务相关信息
$serviceArr = array();
foreach ($fileArr as $file) {
    if (!is_file(SERVICE_DIR . DS . $file) || !preg_match('/Service\.php$/', $file)) {
        continue;
    }
    // 类名
    $className = str_replace('.php', '', $file);
    $refClassObj = new ReflectionClass($className);
    $refMethodObjArr = $refClassObj->getMethods();
    foreach ($refMethodObjArr as $refMethodObj) {
        // 方法名
        $methodName = $refMethodObj->name;
        // 跳过构造函数,非公有方法等
        if ($methodName === '__construct' || $methodName === '__destruct' || $methodName === 'outputResult' || !$refMethodObj->isPublic()) {
            continue;
        }
        // 获取方法参数
        $refParamObjArr = $refMethodObj->getParameters();
        $paramArr = array();
        foreach ($refParamObjArr as $refParamObj) {
            $paramArr[] = '$' . $refParamObj->name;
        }
        $serviceArr[$className][$methodName] = array(
            'params' => '[' . implode(',', $paramArr) . ']',
        );
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Poster</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
label {
	display:inline-block;
	width:70px;
	text-align:right;
}
input {
	width:400px;
}
</style>
<script type="text/javascript">
function $(s) {
    return document.getElementById(s);
}
var services = <?php echo json_encode($serviceArr); ?>;
window.onload=function(){
    var s=$('service');
    var a=$('action');
    var p=$('params');
    var str='<option value="">--select service--</option>';
    for(var o in services) {
        str+='<option value="'+o+'">'+o+'</option>';
    }
    s.innerHTML=str;
    s.onchange=function(){
        var key=s.options[s.selectedIndex].value;
        var str='<option value="">--select action--</option>';
        if(key!='') {
            var obj=services[key];
            for(var o in obj) {
                str+='<option value="'+o+'">'+o+'</option>';
            }
        }
        a.innerHTML=str;
        p.value='';
    }
    a.onchange=function(){
        var key1=s.options[s.selectedIndex].value;
        var key2=a.options[a.selectedIndex].value;
        var str='';
        if(key1!='' && key2!='') {
            str=services[key1][key2].params;
        }
        p.value=str;
    }
}
</script>
</head>
<body>
<iframe name="frame" src="" width="100%" height="300"></iframe>
<form action="http://app27790.qzoneapp.com/gateway.php" method="post" target="frame">
  <div><label for="service">service：</label><select id="service" name="service"><option value="">--select service--</option></select></div>
  <div><label for="action">action：</label><select id="action" name="action"><option value="">--select action--</option></select></div>
  <div><label for="params">params：</label><input type="text" id="params" name="params" value="" /></div>
  <div><button type="submit">submit</button>&nbsp;<button type="reset">reset</button></div>
</form>
</body>
</html>
