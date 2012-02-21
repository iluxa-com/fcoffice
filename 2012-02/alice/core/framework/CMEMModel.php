<?php
/**
 * CMEM模型基类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
abstract class CMEMModel {
    /**
     * CMEM驱动名称(CMEMHelper)
     * @var string
     */
    private $_cmemDriver = 'CMEMHelper';
    /**
     * CMEM辅助类实例数组
     * @var array
     */
    private static $_cmemHelper = array();
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup;
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix;
    /**
     * 静态Key数组
     * @var array
     */
    protected $_staticKeyArr = array();
    /**
     * 动态Key数组
     * @var array
     */
    protected $_dynamicKeyArr = array();
    /**
     * 轮转ID
     * @var int
     */
    private $_circleId = 0;

    /**
     * 构造函数
     * @param string/int $key1,$key2,... 可变参数,每个参数代表一个子键(按需提供)
     */
    public function __construct() {
        $args = func_get_args();
        list($serverType, $moduleName) = explode('.', $this->_serverGroup);
        switch ($serverType) {
            case 'main':
                // 设置轮转ID(固定为0)
                $this->_circleId = 0;
                break;
            case 'circle': // 必须提供轮转ID(如果提供了参数,则取第一个参数;否则取当前登录用户的用户ID)
                if (empty($args) || $args[0] === NULL) {
                    $args[0] = App::get('user_id');
                }
                // 设置轮转ID(固定为第一个参数)
                $this->_circleId = $args[0];
                break;
            default:
                throw new UserException(UserException::ERROR_SYSTEM, 'UNDEFINED_SERVER_GROUP', $this->_serverGroup);
                break;
        }
    }

    /**
     * 获取CMEM辅助类的实例
     * @return CMEMHelper
     */
    public function MH() {
        $configArr = App::getServerConfig('CMEMServer', $this->_serverGroup, $this->_circleId);
        $key = $configArr[0] . ':' . $configArr[1];
        if (!isset(self::$_cmemHelper[$key])) {
            self::$_cmemHelper[$key] = App::getInst($this->_cmemDriver, $configArr, false, $key);
        }
        self::$_cmemHelper[$key]->getConn()->setOption(Memcached::OPT_PREFIX_KEY, $this->_storeKeyPrefix . ':' . $this->_circleId . ':');
        return self::$_cmemHelper[$key];
    }

    /**
     * 更新单个动态Key的值
     * @param string $hashKey
     * @param int $offset
     * @return int
     */
    public function hIncrBy($hashKey, $offset) {
        if (!array_key_exists($hashKey, $this->_dynamicKeyArr)) {
            throw new UserException(UserException::ERROR_SYSTEM, 'UNDEFINED_HASH_KEY', $hashKey);
        }
        if ($offset > 0) {
            return $this->MH()->increment($hashKey, $offset);
        } else {
            return $this->MH()->decrement($hashKey, $offset);
        }
    }

    /**
     * 设置多个动态Key的值
     * @param array $dataArr
     * @return bool
     */
    public function hMset($dataArr) {
        $diffKeyArr = array_diff_key($dataArr, $this->_dynamicKeyArr);
        if (!empty($diffKeyArr)) {
            throw new UserException(UserException::ERROR_SYSTEM, 'UNDEFINED_HASH_KEY');
        }
        return $this->MH()->setMulti($dataArr);
    }

    /**
     * 获取多个动态Key的值
     * @param array $hashKeyArr
     * @return array/false
     */
    public function hMget($hashKeyArr) {
        return $this->MH()->getMulti($hashKeyArr);
    }

    /**
     * 删除动态Key
     * @param string $hashKey
     * @return bool
     */
    public function hDel($hashKey) {
        return $this->MH()->delete($hashKey);
    }

    /**
     * 获取所有动态Key的值
     * @return array/false
     */
    public function hGetAll() {
        return $this->hMget($this->_dynamicKeyArr);
    }
}
?>