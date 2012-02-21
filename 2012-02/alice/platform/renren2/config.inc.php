<?php
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 系统基本配置
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * 平台设置(平台值单词首字母大写)
 */
App::set('Platform', 'Renren2');


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 网关,资源路径设置
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * 网关URL(必须以"/"结尾)
 */
define('GATEWAY_URL', 'http://newalrr.fanhougame.com/');
//define('GATEWAY_URL', 'http://newalrr.fanhouapp.com/');

/**
 * 资源URL(必须以"/"结尾)
 */
define('RESOURCE_URL', 'http://res.fanhougame.com/newalrr/');


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 开关设置(1=打开,0=关闭)
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * 记录异常(按需开启)
 */
define('LOG_EXCEPTION', 1);

/**
 * 记录失败的服务(按需开启)
 */
define('LOG_FAIL_SERVICE', 1);


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 应用信息配置
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * 应用URL(必须以"/"结尾)
 */
define('APP_URL', 'http://apps.renren.com/dreaming_adventures_new/');
/**
 * 应用发布时间
 */
define('APP_PUB_DATE', '2011-10-19');


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 数据库,缓存服务器等配置
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * MySQL服务器配置
 * array(
 *     类型.模块 => array(
 *         节点编号 => array($host, $port, $username, $password, $dbname, $charset),
 *     )
 * )
 */
App::set(
                'MySQLServer',
                array(
                    'main.public' => array(
                        360 => array('192.168.1.42', 3306, 'P0108playonline', 'P0108online@fhdbv5!', 'P01081000000', 'utf8'),
                    ),
                    'circle.log' => array(
                        120 => array('192.168.1.42', 3306, 'P0108playonline', 'P0108online@fhdbv5!', 'P01082001120', 'utf8'),
                        240 => array('192.168.1.42', 3306, 'P0108playonline', 'P0108online@fhdbv5!', 'P01082121240', 'utf8'),
                        360 => array('192.168.1.42', 3306, 'P0108playonline', 'P0108online@fhdbv5!', 'P01082241360', 'utf8'),
                    ),
                    'main.stat' => array(
                        360 => array('192.168.1.42', 3306, 'P0108playonline', 'P0108online@fhdbv5!', 'P01083000000', 'utf8'),
                    ),
                )
);

/**
 * Redis服务器配置
 * array(
 *     类型.模块 => array(
 *         节点编号 => array($host, $port),
 *     )
 * )
 */
App::set(
                'RedisServer',
                array(
                    'main.public' => array(
                        360 => array('192.168.1.42', 6300, 0),
                    ),
                    'main.relation' => array(
                        360 => array('192.168.1.42', 6301, 0),
                    ),
                    'main.stat' => array(
                        360 => array('192.168.1.42', 6302, 0),
                    ),
                    'circle.user' => array(
                        360 => array('192.168.1.42', 6350, 0),
                    ),
                    'circle.neice' => array( // 内测奖励相关数据临时存放
                        360 => array('192.168.1.42', 6350, 2),
                    ),
                    'circle.friend' => array(
                        360 => array('192.168.1.42', 6351, 0),
                    ),
                )
);


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 其他配置
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * 默认好友
 */
App::set(
                'DefaultFriend',
                array(
                    'exp' => 153805510,
                    'head_img' => 'http://hdn.xnimg.cn/photos/hdn521/20110513/2315/tiny_SyOH_1122m019118.jpg',
                    'sns_uid' => '368288392',
                    'user_id' => 1,
                    'username' => '莱利亚',
                    'wish_items' => '',
                    'is_default' => 1,
                )
);

/**
 * 排行榜更新间隔(s)
 */
App::set('TopDataUpdateInterval', 3600);

/**
 * 七夕情人节活动标志(2011-08-06 00:00:00 - 2011-08-17 00:00:00)
 */
//if (CURRENT_TIME >= 1312560000 && CURRENT_TIME < 1313510400) {
//    define('FESTIVAL_7_7_FLAG', 1);
//} else if (CURRENT_TIME >= 1313510400) {
//    define('FESTIVAL_7_7_FLAG', 2);
//}

/**
 * 10人宝箱活动标志(2011-08-06 00:00:00 - ?)
 */
if (CURRENT_TIME >= 1312560000) {
    define('ACTIVITY_OPEN_CHEST', 1);
}

/**
 * 百人达活动标志(2011-08-06 00:00:00 - ?)
 */
if (CURRENT_TIME >= 1312560000) {
    define('ACTIVITY_INVITE_FRIEND', 1);
}

/**
 * 资源配置文件签名
 */
if (isset($_SERVER['SERVER_ADDR']) && in_array($_SERVER['SERVER_ADDR'], array('122.11.57.43'))) { // 测试环境
    define('IS_TEST_SERVER', 1);
    define('LOADING_SIGN', '20120105');
    define('RESOURCE_SIGN', '12b2kw4');
} else { // 正式环境
    define('LOADING_SIGN', '20120105');
    define('RESOURCE_SIGN', '12b2kw4');
}
?>