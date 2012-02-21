<?php
// 脚本开始执行时间
$startTime = microtime(true);
// 引入全局配置文件
require_once 'config.php';
// 获取请求来源,服务,动作,参数,请求序号及上次登录时间
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
    defined('LOG_EXCEPTION') && constant('LOG_EXCEPTION') && Debug::log(Debug::getExceptionMsg($e), Debug::TYPE_SERVICE_EXCEPTION + $e->getCode());
    echo '{"ret":' . $e->getCode() . '}';
}
if (App::get('Platform') === 'Devel') { // 本地开发环境,记录请求信息,用来分析BUG产生原因
    $filename = '/tmp/alice_request_log/' . date('Y-m-d-H') . '.log';
    $post = $_POST;
    $get = $_GET;
    if (defined('GPC_SLASHES_ADDED')) {
        !empty($post) && App::stripSlashes($post);
        !empty($get) && App::stripSlashes($get);
    }
    $dataArr = array(
        'post' => $post,
        'get' => $get,
        'session' => $_SESSION,
        'error_code' => isset($e) ? $e->getCode() : '',
        'exec_time' => microtime(true) - $startTime, // 执行时间
        'request_time' => date('r', CURRENT_TIME), // 请求时间
    );
    file_put_contents($filename, json_encode($dataArr) . "\n", FILE_APPEND);
}
?>