<?php
/**
 * 应用类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class App {
    /**
     * 访问控制键
     * @var string
     */
    const ACL_KEY = 'ALICE_ACL';
    /**
     * 系统内部全局变量
     * @var array
     */
    private static $_GLOBALS = array();
    /**
     * 实例数组
     * @var array
     */
    private static $_instArr = array();
    /**
     * 事务队列是否开启
     * @var bool
     */
    private static $_transStarted = false;
    /**
     * DB对象数组
     * @var array
     */
    private static $_dbArr = array();

    /**
     * 初始化
     * @param string $path1,$path2,... 可变参数,每个参数为一个有效物理路径
     */
    public static function init() {
        // 设置包含文件自动搜寻路径
        $pathArr = func_get_args();
        if (!empty($pathArr)) {
            set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $pathArr));
        }
        // 注册类文件自动加载函数
        spl_autoload_register(array(__CLASS__, 'autoload'));
        // 设置自定义错误处理函数
        set_error_handler(array(__CLASS__, 'errorHandler'));
        // GET/POST/COOKIE超全局数组特殊字符转义处理
        self::__addSlashesForGPC();
        // 启动SESSION会话
        self::__sessionStart();
    }

    /**
     * 自动加载类文件
     * @param string $class 类名
     */
    public static function autoload($class) {
        require_once($class . '.php');
    }

    /**
     * 错误处理函数
     * @param int $errNo 错误级别
     * @param string $errStr 错误信息
     * @param string $errFile 错误所在文件名
     * @param int $errLine 错误所在行号
     */
    public static function errorHandler($errNo, $errStr, $errFile, $errLine) {
        $dataArr = array(
            'err_no' => $errNo,
            'err_str' => $errStr,
            'err_file' => $errFile,
            'err_line' => $errLine,
        );
        Debug::log($dataArr, Debug::TYPE_RUNTIME_ERROR + UserException::ERROR_RUNTIME_ERROR);
        exit('{"ret":' . UserException::ERROR_RUNTIME_ERROR . '}');
    }

    /**
     * 递归将数组字符串元素中的特殊字符转义（单引号（'）、双引号（"）、反斜线（\）、NUL（NULL字符））
     * @param &array $arr 数组
     */
    public static function addSlashes(&$arr) {
        foreach ($arr as &$val) { // 注意这里的$val一定要是引用
            if (is_string($val)) {
                $val = addslashes($val);
            } else if (is_array($val)) {
                call_user_func(array(__CLASS__, __FUNCTION__), $val);
            }
        }
    }

    /**
     * 递归将数组中的特殊字符转义取消（单引号（'）、双引号（"）、反斜线（\）、NUL（NULL字符））
     * @param &array $arr 数组
     */
    public static function stripSlashes(&$arr) {
        foreach ($arr as &$val) { // 注意这里的$val一定要是引用
            if (is_string($val)) {
                $val = stripslashes($val);
            } else if (is_array($val)) {
                call_user_func(array(__CLASS__, __FUNCTION__), $val);
            }
        }
    }

    /**
     * 对GET/POST/COOKIE超全局数组中的特殊字符进行转义处理
     */
    private static function __addSlashesForGPC() {
        // 如果系统没有开启对超全局变量中的特殊字符自动转义，则手动进行转义之
        if (!get_magic_quotes_gpc() && !defined('GPC_SLASHES_ADDED')) {
            define('GPC_SLASHES_ADDED', 1);
            !empty($_GET) && self::addSlashes($_GET);
            !empty($_POST) && self::addSlashes($_POST);
            !empty($_COOKIE) && self::addSlashes($_COOKIE);
        }
    }

    /**
     * 设置变量
     * @param string $key 键
     * @param mixed $val 值
     */
    public static function set($key, $val) {
        self::$_GLOBALS[$key] = $val;
    }

    /**
     * 获取变量
     * @param string $key 键
     * @param mixed $notExistsRet 键不存在时的返回值(指定为非NULL值时生效)
     * @return mixed
     */
    public static function get($key, $notExistsRet = NULL) {
        if (array_key_exists($key, self::$_GLOBALS)) {
            return self::$_GLOBALS[$key];
        } else if ($notExistsRet !== NULL) {
            return $notExistsRet;
        } else {
            throw new UserException(UserException::ERROR_SYSTEM, 'GET_VARIABLE_THAT_NOT_EXISTS', $key);
        }
    }

    /**
     * 检查指定数据库服务器是否需要添加到事务队列当中
     * @param string $host
     * @return bool
     */
    public static function isNeedJoinInTrans($host) {
        return self::$_transStarted && !isset(self::$_dbArr[$host]);
    }

    /**
     * 将指定数据库服务器添到事务队列当中
     * @param string $host
     * @param object $db
     */
    public static function joinInTrans($host, $db) {
        self::$_dbArr[$host] = $db;
    }

    /**
     * 开启事务队列
     */
    public static function startTrans() {
        if (self::$_transStarted) {
            throw new UserException(UserException::ERROR_SYSTEM, 'TRANSACTION_ALREADY_STARTED');
        } else {
            self::$_transStarted = true;
            self::$_dbArr = array();
        }
    }

    /**
     * 提交事务队列
     */
    public static function commitTrans() {
        if (self::$_transStarted) {
            foreach (self::$_dbArr as $db) {
                $db->commit();
            }
        }
        self::$_transStarted = false;
        self::$_dbArr = array();
    }

    /**
     * 回滚事务队列
     */
    public static function rollbackTrans() {
        if (self::$_transStarted) {
            foreach (self::$_dbArr as $db) {
                $db->rollback();
            }
        }
        self::$_transStarted = false;
        self::$_dbArr = array();
    }

    /**
     * 获取访问者IP
     * @return string
     */
    public static function getIP() {
        // 此顺序不要更改
        $keyArr = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR');
        foreach ($keyArr as $key) {
            if (isset($_SERVER[$key])) {
                return $_SERVER[$key];
            }
        }
        return '0.0.0.0';
    }

    /**
     * 获取指定类的实例
     * @param string $class 类名
     * @param mixed $args 类实例化时传递给构造函数的参数(默认为NULL)
     * @param bool $singleton 是否为单例(默认为true,即单例模式)
     * @param string $key 存储键,用来区分同一个类的不同实例
     * @return object
     */
    public static function getInst($class, $args = NULL, $singleton = true, $key = NULL) {
        if ($key === NULL) {
            $key = ($singleton || !isset(self::$_instArr[$class])) ? 0 : count(self::$_instArr[$class]);
        }
        if (!isset(self::$_instArr[$class][$key])) {
            if ($args === NULL) {
                self::$_instArr[$class][$key] = new $class();
            } else {
                self::$_instArr[$class][$key] = new $class($args);
            }
        }
        return self::$_instArr[$class][$key];
    }

    /**
     * 服务动作调度函数
     * @param string $from 请求来源(admin表示为后台)
     * @param string $service 服务
     * @param string $action 动作
     * @param string $params 参数(json编码的字符串,如:[1, 2.5, "str", true];)
     * @param string $index 请求序号(AS端使用,PHP端原样返回给AS端)
     * @param int $lastLogin 上次登录时间
     */
    public static function dispatch($from, $service, $action, $params = NULL, $index = NULL, $lastLogin = NULL) {
        if ($from === 'admin') { // 服务文件路径
            $filename = SERVICE_DIR . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . $service . '.php';
        } else {
            $filename = SERVICE_DIR . DIRECTORY_SEPARATOR . $service . '.php';
        }
        // 必要的安全检查
        if (!file_exists($filename) || $service === '' || strpos($service, '.') !== false) {
            throw new UserException(UserException::ERROR_SYSTEM, 'INVALID_SERVICE', $service);
        }
        // 引入服务类
        require_once($filename);
        // 检查服务类是否存在(不调用__autoload),不存在时抛出异常
        if (!class_exists($service, false)) {
            throw new UserException(UserException::ERROR_SYSTEM, 'SERVICE_CLASS_NOT_FOUND', $service);
        }
        // 获取服务类的实例
        $obj = self::getInst($service, array($service, $action, $index, $lastLogin));
        // 检查动作方法是否存在,不存在时抛出异常
        if (!method_exists($obj, $action) || $action === '') {
            throw new UserException(UserException::ERROR_SYSTEM, 'ACTION_NOT_FOUND', $service, $action);
        }
        // 调用服务方法
        if ($params === NULL) {
            call_user_func(array($obj, $action));
        } else {
            $params = json_decode(stripslashes($params), true);
            if ($params === NULL) { // json解码失败,抛出异常
                throw new UserException(UserException::ERROR_SYSTEM, 'INVALID_PARAMS');
            }
            call_user_func_array(array($obj, $action), $params);
        }
        // 输出结果
        $obj->outputResult();
    }

    /**
     * 调用指定的函数
     * @param string $functionName 函数名称
     * @param array $paramArr 参数数组
     * @param int $start 开始日期(Unix时间戳)
     * @param int $end 结束日期(Unix时间戳)
     * @return bool
     */
    public static function callFunc($functionName, $paramArr = NULL, $start = NULL, $end = NULL) {
        if ($start !== NULL) {
            if ($start < CURRENT_TIME) { // 时间还未到
                return true;
            }
        }
        if ($end !== NULL) {
            if ($end > CURRENT_TIME) { // 时间已过了
                return true;
            }
        }
        $filename = PLUGIN_DIR . DIRECTORY_SEPARATOR . strtolower(self::get('Platform')) . DIRECTORY_SEPARATOR . $functionName . '.php';
        if (!file_exists($filename)) { // 文件不存在
            return true;
        }
        require_once($filename);
        if (!function_exists($functionName)) { // 不存在,抛异常
            throw new UserException(UserException::ERROR_SYSTEM, 'PLUGIN_FUNCTION_NOT_EXISTS', $functionName);
        } else if ($paramArr === NULL) {
            return call_user_func($functionName);
        } else {
            return call_user_func_array($functionName, $paramArr);
        }
    }

    /**
     * 启动SESSION会话
     */
    private static function __sessionStart() {
        if (session_id() === '') {
            session_start();
        }
    }

    /**
     * 注册当前用户信息进SESSION
     * @param array $dataArr 数据数组array('user_id' => 9527, 'sns_uid' => '$snsUid', 'sns_session_key' => '$snsSessionKey', 'sns_user_info' => '$snsUserInfo', 'login_time' => CURRENT_TIME)
     */
    public static function setCurrentUser($dataArr) {
        $_SESSION[self::ACL_KEY] = $dataArr;
    }

    /**
     * 获取SESSION中当前用户信息
     * @param bool $throwException 未取到时是否抛异常(默认为true)
     * @return array/NULL 参见setCurrentUser中的$dataArr参数
     */
    public static function getCurrentUser($throwException = true) {
        if (isset($_SESSION[self::ACL_KEY])) { // 存在,直接返回
            return $_SESSION[self::ACL_KEY];
        } else if ($throwException) { // 不存在,抛出异常
            throw new UserException(UserException::ERROR_SESSION_EXPIRED, 'SESSION_EXPIRED');
        } else {
            return NULL;
        }
    }

    /**
     * 从SESSION中移除当前用户信息
     */
    public static function removeCurrentUser() {
        unset($_SESSION[self::ACL_KEY]);
    }

    /**
     * 获取SNS实例
     * @return SNSDevel
     */
    public static function getSNS() {
        return self::getInst('SNS' . self::get('Platform'));
    }

    /**
     * 获取服务器配置
     * @param string $serverType 服务器类型
     * @param string $serverGroup 服务器组
     * @param int $circleId 轮转ID
     * @return array
     */
    public static function getServerConfig($serverType, $serverGroup, $circleId) {
        $configsArr = self::get($serverType);
        if (!isset($configsArr[$serverGroup])) {
            throw new UserException(UserException::ERROR_SYSTEM, 'SERVER_GROUP_NOT_FOUND', $serverGroup);
        }
        if ($circleId === NULL) { // 主库,随机选取一个
            return $configsArr[$serverGroup][array_rand($configsArr[$serverGroup])];
        }
        switch (self::get('Platform')) {
            case 'Pengyou':
            case 'Qzone':
            case 'Devel':
            case 'Local':
                $nodeId = intval($circleId / 500000); // 分段
                break;
            default :
                $nodeId = $circleId % 360; // 求模
                break;
        }
        foreach ($configsArr[$serverGroup] as $nodeIdMax => $configArr) {
            if ($nodeId < $nodeIdMax) {
                return $configArr;
            }
        }
        throw new UserException(UserException::ERROR_SYSTEM, 'SERVER_NODE_NOT_FOUND', $nodeId);
    }
}
?>