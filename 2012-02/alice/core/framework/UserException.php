<?php
/**
 * 用户异常类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class UserException extends Exception {
    /**
     * 系统错误
     * @var int
     */
    const ERROR_SYSTEM = 100;
    /**
     * SESSION过期(未登录或登录超时)
     * @var int
     */
    const ERROR_SESSION_EXPIRED = 101;
    /**
     * 帐号被屏蔽
     * @var int
     */
    const ERROR_ACCOUNT_BLOCKED = 102;
    /**
     * 需要重新登录
     * @var int
     */
    const ERROR_NEED_RELOGIN = 103;
    /**
     * 服务繁忙
     * @var int
     */
    const ERROR_SERVER_BUSY = 104;
    /**
     * 扩展未加载
     * @var int
     */
    const ERROR_EXTENSION_NOT_LOADED = 105;
    /**
     * memcache/memcached错误
     * @var int
     */
    const ERROR_MEMCACHE = 106;
    const ERROR_MEMCACHED = 107;
    /**
     * MySQL错误
     * @var int
     */
    const ERROR_MYSQL = 108;
    const ERROR_MYSQLI = 109;
    const ERROR_PDO = 110;
    /**
     * Redis错误
     * @var int
     */
    const ERROR_REDIS = 111;
    /**
     * SNS 参数错误
     * @var int
     */
    const ERROR_SNS_INVALID_PARAM = 112;
    /**
     * 帐号重复登录
     * @var int
     */
    const ERROR_ACCOUNT_MULTI_LOGIN = 113;
    /**
     * 体力不够
     * @var int
     */
    const ERROR_NOT_ENOUGH_ENERGY = 114;
    /**
     * SNS session 过期
     * @var int
     */
    const ERROR_SNS_SESSION_EXPIRED = 115;
    /**
     * CURL ERROR
     * @link http://curl.haxx.se/libcurl/c/libcurl-errors.html
     * @var int
     */
    const ERROR_CURL = 116;
    /**
     * SNS API BAD RETURN
     * @var int
     */
    const ERROR_SNS_API_BAD_RETURN = 117;
    /**
     * SNS API FAIL
     * @var int
     */
    const ERROR_SNS_API_FAIL = 118;
    /**
     * 道具数量达到上限
     * @var int
     */
    const ERROR_ITEM_REACH_MAX = 119;
    /**
     * 客户端与服务端时间不同步
     * @var int
     */
    const ERROR_NEED_SYNC_TIME = 120;
    /**
     * 用户不存在
     * @var int
     */
    const ERROR_USER_NOT_EXISTS = 121;
    /**
     * 分享任务错误
     * @var int
     */
    const ERROR_SLOT_TASK = 122;
    /**
     * 插件调用错误
     * @var int
     */
    const ERROR_PLUGIN = 123;
    /**
     * 运行时错误
     * @var int
     */
    const ERROR_RUNTIME_ERROR = 124;

    /**
     * 构造函数
     * @param int $code 错误编号
     * @param string $format 错误消息格式字符串
     * @param mixed $arg1,$arg2,... 可变参数(格式参数)
     */
    public function __construct($code = -1, $format = 'UNDEFINED_ERROR') {
        // 获取参数数组
        $args = func_get_args();
        // 移去第一个参数
        array_shift($args);
        // 移去第二个参数
        array_shift($args);
        // 解析错误消息格式字符串配置到数组
        $formatArr = parse_ini_file(FRAMEWORK_DIR . DIRECTORY_SEPARATOR . 'conf' . DIRECTORY_SEPARATOR . 'Msg.php');
        // 格式化消息
        if (is_array($formatArr) && array_key_exists($format, $formatArr)) {
            $msg = vsprintf($formatArr[$format], $args);
        } else {
            $msg = vsprintf($format, $args);
        }
        // 调用父类的构造函数
        parent::__construct($msg, $code);
    }

    /**
     * 重载__toString()方法
     * @return string
     */
    public function __toString() {
        $str = '';
        $str .= __CLASS__;
        $str .= "\n";
        $str .= $this->getCode();
        $str .= "\n";
        $str .= $this->getFile() . '(' . $this->getLine() . ')';
        $str .= "\n";
        $str .= $this->getMessage();
        $str .= "\n";
        $str .= $this->getTraceAsString();
        return $str;
    }
}
?>