<?php
/**
 * TX统一数据接入CLogger类
 *
 * @link http://wiki.open.qq.com/wiki/%E8%85%BE%E8%AE%AF%E7%BD%97%E7%9B%98%E4%BB%8B%E7%BB%8D
 * @link http://wiki.opensns.qq.com/wiki/%E7%BB%9F%E4%B8%80%E6%95%B0%E6%8D%AE%E6%8E%A5%E5%85%A5
 * @author xianlinli@gmail.com
 * @package Alice
 */
class CLogger {
    /**
     * 经分，安装数据上报
     * @var int
     */
    const LT_NORMAL = 0;
    /**
     * 模块间调用
     * @var int
     */
    const LT_MOD = 1;
    /**
     * 互娱明星计划应用的经分、安全数据上报
     * @var int
     */
    const LT_BASE = 2;
    /**
     * 成功
     * @var int
     */
    const RET_SUCC = 0;
    /**
     * 初始化错误
     * @var int
     */
    const RET_ERR_API_INIT = 1;
    /**
     * log数据长度错误
     * @var int
     */
    const RET_ERR_API_LOGLEN =2;
    /**
     * 发送缓冲区错误
     * @var int
     */
    const RET_ERR_API_SENDBUFFNULL = 4;
    /**
     * 接收缓冲区错误
     * @var int
     */
    const RET_ERR_API_READBUFFNULL = 5;
    /**
     * 发送缓冲区长度错误
     * @var int
     */
    const RET_ERR_API_SENDBUFFLEN = 6;
    /**
     * unix socket连接错误
     * @var int
     */
    const RET_ERR_API_CONN = 7;
    /**
     * unix socket发送错误
     * @var int
     */
    const RET_ERR_API_SEND = 8;
    /**
     * unix socket接收错误
     * @var int
     */
    const RET_ERR_API_READ = 9;
    /**
     * unix socket接收包长错误
     * @var int
     */
    const RET_ERR_API_READLEN = 10;
    /**
     * 统一数据接入Agent系统繁忙
     * @var int
     */
    const RET_ERR_SVR_SYSBUSY = 10000;
    /**
     * 获取Appname失败
     * @var int
     */
    const RET_ERR_SVR_INVALIDAPPNAME = 10002;
    /**
     * 写数据失败
     * @var int
     */
    const RET_ERR_SVR_WRITEDATA = 10004;
    /**
     * 取数据失败
     * @var int
     */
    const RET_ERR_SVR_FETCHDATA = 10005;
    /**
     * 无效的logtype
     * @var int
     */
    const RET_ERR_SVR_LOGTYPE = 10006;
    /**
     * 读配置出错
     * @var int
     */
    const RET_ERR_SVR_READCONFIG = 10007;
    /**
     * 无效的logtype
     * @var int
     */
    const RET_ERR_SVR_CMD = 10008;
    /**
     * 无效的命令字
     * @var int
     */
    const RET_ERR_SVR_CMD = 10009;

    /**
     * 构造函数
     * @param string $appname 应用名称
     * @return int 0/1(0=成功,1=失败)
     */
    public function __construct($appname) {

    }

    /**
     * 类似于urlencode函数,对'&','=','%'等特殊字符进行替换
     * @param string $srcData 原字符串
     * @param string $dstData 编码后的字符串
     * @return int 0/其他(0=成功,其他=失败)
     */
    public function encode($srcData, $dstData) {

    }

    /**
     * 数据上报函数,一般用于非落地数据的上报
     * @param int $logType 数据上报的类型
     * @param string $data 已经编码后的数据,业务需要直接传递格式为"k1=v1&k2=v2"的数据
     * @param bool $fallFlag 是否将上报的日志数据落地(true=落地,false=不落地)
     * @return int 0/其他(0=成功,其他=失败)
     */
    public function write_baselog($logType, $data, $fallFlag) {

    }

    /**
     * 返回调用失败时的错误信息
     * @return string/false string为调用失败时的错误信息字符串,出错返回false
     */
    public function get_errmsg() {

    }

    /**
     * 数据上报函数,一般用于非落地数据的上报(非公开)
     * @param int $logType 数据上报的类型
     * @param string $data 已经编码后的数据,业务需要直接传递格式为"k1=v1&k2=v2"的数据
     * @param bool $fallFlag 是否将上报的日志数据落地(true=落地,false=不落地)
     * @return int 0/其他(0=成功,其他=失败)
     */
    public function write_log($logType, $data, $fallFlag) {

    }

    /**
     * 提交write_log写的数据(非公开)
     * @return int 0/其他(0=成功,其他=失败)
     */
    public function commit() {

    }
}
?>