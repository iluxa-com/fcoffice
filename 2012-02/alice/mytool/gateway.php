<?php
// 引入全局配置文件
require_once '../config.php';
// 获取请求来源,服务,动作,参数,请求序号及上次登录时间
require_once 'firephp/fb.php';
if (isset($_POST['service'])) { // AS
    $from = isset($_POST['from']) ? $_POST['from'] : '';
    $service = isset($_POST['service']) ? $_POST['service'] : 'DefaultService';
    $action = isset($_POST['action']) ? $_POST['action'] : 'sayHello';
    $params = isset($_POST['params']) ? $_POST['params'] : '[]';
    $index = isset($_POST['index']) ? $_POST['index'] : '';
    $lastLogin = isset($_POST['t']) ? $_POST['t'] : NULL;
} else { // 后台
    $from = isset($_GET['from']) ? $_GET['from'] : 'admin';
    $service = isset($_GET['service']) ? $_GET['service'] : '';
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    $params = NULL;
    $index = NULL;
    $lastLogin = NULL;
}

// 输出内容编码设置
header('Content-Type: text/html; charset=utf-8');

// 服务调用
try {
    App::dispatch($from, $service, $action, $params, $index, $lastLogin);
} catch (Exception $e) { // 捕捉所有异常
    if (defined('DEBUG_MODE') && constant('DEBUG_MODE')) { // 调试模式,输出详细信息
        exit(Debug::getExceptionMsg($e));
    } else { // 非调式模式,仅输出错误代码
        Debug::log(Debug::getExceptionMsg($e), Debug::TYPE_SERVICE_EXCEPTION + $e->getCode());
        exit('{"ret":' . $e->getCode() . '}');
    }
}
?>