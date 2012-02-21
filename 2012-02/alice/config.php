<?php
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// PHP相关设置(可在php.ini中配置)
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * 设置错误报告级别
 */
//error_reporting(E_ALL | E_STRICT);

/**
 * 使用memcache存储session
 */
//ini_set('session.save_handler', 'memcache');
//ini_set('session.save_path', 'tcp://127.0.0.1:11211');

/**
 * 使用memcached存储session
 * @link http://www.php.net/manual/en/memcached.sessions.php
 */
//ini_set('session.save_handler', 'memcached');
//ini_set('session.save_path', '127.0.0.1:11211');

/**
 * 设置默认时区
 */
//date_default_timezone_set('Asia/Shanghai');
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 初始化代码
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * 当前服务器Unix时间戳
 */
define('CURRENT_TIME', time());

/**
 * 目录分隔符简写,Linux系统下为"/",Windows系统下"\"
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * 基准路径(以当前文件所在路径为基准,其他路径都参照此基准)
 */
define('BASE_DIR', !isset($_COOKIE['ALICE_TEST_ENV']) ? dirname(__FILE__) : dirname(dirname(__FILE__)) . '_test' . DS . 'src');

/**
 * 路径常量定义(后面都不带"/"或"\")
 */
define('CORE_DIR', BASE_DIR . DS . 'core');
define('FRAMEWORK_DIR', CORE_DIR . DS . 'framework');
define('HELPER_DIR', FRAMEWORK_DIR . DS . 'helper');
define('SNS_DIR', CORE_DIR . DS . 'sns');
define('MODEL_DIR', BASE_DIR . DS . 'model');
define('REDIS_MODEL_DIR', BASE_DIR . DS . 'model' . DS . 'redis');
define('SQL_MODEL_DIR', BASE_DIR . DS . 'model' . DS . 'sql');
define('SERVICE_DIR', BASE_DIR . DS . 'service');
define('PLUGIN_DIR', BASE_DIR . DS . 'plugin');

/**
 * 引入应用类
 */
require_once(FRAMEWORK_DIR . DS . 'App.php');

/**
 * 应用初始化
 */
App::init(FRAMEWORK_DIR, HELPER_DIR, SNS_DIR, MODEL_DIR, REDIS_MODEL_DIR, SQL_MODEL_DIR);


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 加载子配置文件
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * 根据不同的Host,自动加载对应的子配置文件
 */
switch ($_SERVER['HTTP_HOST']) {
    case 'alrr.fanhougame.com': // 人人网
        require_once BASE_DIR . '/platform/renren/config.inc.php';
        break;
    case 'newalrr.fanhougame.com': // 人人网(新版)
        require_once BASE_DIR . '/platform/renren2/config.inc.php';
        break;
    case 'alice4399.fanhouapp.com': // 4399
        require_once BASE_DIR . '/platform/4399/config.inc.php';
        break;
    case 'alvv.fanhougame.com': // 版号
        require_once BASE_DIR . '/platform/test/config.inc.php';
        break;
    case 'app27790.qzoneapp.com': // TX-朋友
        require_once BASE_DIR . '/platform/pengyou/config.inc.php';
        break;
    case 'app27790.qzone.qzoneapp.com': // TX-Qzone
        require_once BASE_DIR . '/platform/qzone/config.inc.php';
        break;
    case 'alice-local.fanhougame.com': // 本机开发
        require_once BASE_DIR . '/platform/local/config.inc.php';
        break;
    default: // 本地开发
        require_once BASE_DIR . '/platform/devel/config.inc.php';
        break;
}
?>