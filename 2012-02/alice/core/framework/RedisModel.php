<?php
/**
 * Redis模型基类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
abstract class RedisModel {
    /**
     * Redis驱动名称(RedisHelper)
     * @var string
     */
    private $_redisDriver = 'RedisHelper';
    /**
     * Redis辅助类实例数组
     * @var array
     */
    private static $_redisHelper = array();
    /**
     * 轮转ID
     * @var int
     */
    private $_circleId;
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix;
    /**
     * 存储键
     * @var string
     */
    protected $_storeKey;

    /**
     * 构造函数
     * @param string/int $key1,$key2,... 可变参数,每个参数代表一个子键(按需提供)
     */
    public function __construct() {
        $args = func_get_args();
        list($serverType, $moduleName) = explode('.', $this->_serverGroup);
        switch ($serverType) {
            case 'main':
                // 设置轮转ID(固定为NULL)
                $this->_circleId = NULL;
                if (empty($args)) {
                    $this->_storeKey = $this->_storeKeyPrefix;
                } else {
                    $this->_storeKey = $this->_storeKeyPrefix . ':' . implode(':', $args);
                }
                break;
            case 'circle': // 必须提供轮转ID(如果提供了参数,则取第一个参数;否则取当前登录用户的用户ID)
                if (empty($args) || $args[0] === NULL) {
                    $args[0] = App::get('user_id');
                }
                // 设置轮转ID(固定为第一个参数)
                $this->_circleId = $args[0];
                // 如果用作缓存,则替换第一个参数为CACHE
                if (isset($this->_useForCache) && $this->_useForCache) {
                    $args[0] = 'CACHE';
                }
                $this->_storeKey = $this->_storeKeyPrefix . ':' . implode(':', $args);
                break;
            default:
                throw new UserException(UserException::ERROR_SYSTEM, 'UNDEFINED_SERVER_GROUP', $this->_serverGroup);
                break;
        }
    }

    /**
     * 获取存储键
     * @return string
     */
    public function getStoreKey() {
        return $this->_storeKey;
    }

    /**
     * 获取Redis辅助类的实例
     * @return RedisHelper
     */
    public function RH() {
        $configArr = App::getServerConfig('RedisServer', $this->_serverGroup, $this->_circleId);
        $key = $configArr[0] . ':' . $configArr[1];
        if (!isset(self::$_redisHelper[$key])) {
            self::$_redisHelper[$key] = App::getInst($this->_redisDriver, $configArr, false, $key);
        }
        self::$_redisHelper[$key]->select($configArr[2]); // 更改数据库索引
        return self::$_redisHelper[$key];
    }

    /**
     * 设置hash表中的指定键的值
     * @param string $hashKey
     * @param string/int $value
     * @return int/bool 1/0/false 1 if value didn't exist and was added successfully, 0 if the value was already present and was replaced, FALSE if there was an error.
     */
    public function hSet($hashKey, $value) {
        return $this->RH()->hSet($this->_storeKey, $hashKey, $value);
    }

    /**
     * 设置hash表中的指定键的值(仅当hashKey不存在时)
     * @param string $hashKey
     * @param string/int $value
     * @return bool TRUE if the field was set, FALSE if it was already present.
     */
    public function hSetNx($hashKey, $value) {
        return $this->RH()->hSetNx($this->_storeKey, $hashKey, $value);
    }

    /**
     * 获取hash表中指定键的值
     * @param string $hashKey
     * @param string/int $defaultValue 默认值
     * @return string/int
     */
    public function hGet($hashKey, $defaultValue = NULL) {
        $val = $this->RH()->hGet($this->_storeKey, $hashKey);
        if ($val !== false) {
            return $val;
        } else if ($defaultValue !== NULL) {
            return $defaultValue;
        } else {
            throw new UserException(UserException::ERROR_SYSTEM, 'HASH_KEY_NOT_EXISTS', $this->_storeKey, $hashKey, json_encode($this->RH()->getConfigArr()));
        }
    }

    /**
     * 删除hash表中指定的键
     * @param string $hashKey
     * @return bool
     */
    public function hDel($hashKey) {
        return $this->RH()->hDel($this->_storeKey, $hashKey);
    }

    /**
     * 同时设置多个
     * @param array $dataArr
     * @return bool
     */
    public function hMset($dataArr) {
        return $this->RH()->hMset($this->_storeKey, $dataArr);
    }

    /**
     * 同时获取多个
     * @param array $keyArr
     * @return array
     */
    public function hMget($keyArr) {
        return $this->RH()->hMget($this->_storeKey, $keyArr);
    }

    /**
     * 对指定的键进行增加或减少操作
     * @param string $hashKey
     * @param int $offset 变更量(正数表示增加,负数表示减少)
     * @return int
     */
    public function hIncrBy($hashKey, $offset) {
        return $this->RH()->hIncrBy($this->_storeKey, $hashKey, $offset);
    }

    /**
     * 获取hash表中所有键的值
     * @return array
     */
    public function hGetAll() {
        return $this->RH()->hGetAll($this->_storeKey);
    }

    /**
     * 获取当前hash表长度
     * @return int
     */
    public function hLen() {
        return $this->RH()->hLen($this->_storeKey);
    }

    /**
     * 检查指定的键是否存在hash表中
     * @param string $hashKey
     * @return bool
     */
    public function hExists($hashKey) {
        return $this->RH()->hExists($this->_storeKey, $hashKey);
    }

    /**
     * 获取Hash表中所有的Key
     * @return array
     */
    public function hKeys() {
        return $this->RH()->hKeys($this->_storeKey);
    }

    /**
     * 将指定元素添加到集合中
     * @param string/int $val
     * @return bool
     */
    public function sAdd($val) {
        return $this->RH()->sAdd($this->_storeKey, $val);
    }

    /**
     * 判断指定的值是否存在集合中
     * @param string/int $val
     * @return bool
     */
    public function sIsMember($val) {
        return $this->RH()->sIsMember($this->_storeKey, $val);
    }

    /**
     * 获取集合中所有元素
     * @return array
     */
    public function sMembers() {
        return $this->RH()->sMembers($this->_storeKey);
    }

    /**
     * 获取集合中元素的个数
     * @return int
     */
    public function sCard() {
        return $this->RH()->sCard($this->_storeKey);
    }

    /**
     * 从集合中删除元素
     * @param string/int $member
     * @return bool
     */
    public function sRemove($member) {
        return $this->RH()->sRemove($this->_storeKey, $member);
    }

    /**
     * 向列表追加元素(加到列表的头部(左边))
     * @param string/int $value
     * @return int The new length of the list in case of success, FALSE in case of Failure.
     */
    public function lPush($value) {
        return $this->RH()->lPush($this->_storeKey, $value);
    }

    /**
     * 向列表追加元素(加到列表的尾部(右边))
     * @param string/int $value
     * @return int The new length of the list in case of success, FALSE in case of Failure.
     */
    public function rPush($value) {
        return $this->RH()->rPush($this->_storeKey, $value);
    }

    /**
     * 获取列表第一个元素,并将其从列表中移除
     * @return string/false string if command executed successfully BOOL FALSE in case of failure (empty list)
     */
    public function lPop() {
        return $this->RH()->lPop($this->_storeKey);
    }

    /**
     * 获取列表最后一个元素,并将其从列表中除除
     * @return string/false string if command executed successfully BOOL FALSE in case of failure (empty list)
     */
    public function rPop() {
        return $this->RH()->rPop($this->_storeKey);
    }

    /**
     * 获取列表元素个数
     * @return int
     */
    public function lLen() {
        return $this->RH()->lLen($this->_storeKey);
    }

    /**
     * 获取列表中指定段的元素
     * @param int $start
     * @param int $end
     * @return array Array containing the values in specified range.
     */
    public function lRange($start = 0, $end = -1) {
        return $this->RH()->lGetRange($this->_storeKey, $start, $end);
    }

    /**
     * 设置值
     * @param string/int $val
     * @return bool
     */
    public function set($val) {
        return $this->RH()->set($this->_storeKey, $val);
    }

    /**
     * 设置值
     * @param int $ttl
     * @param string/int $val
     * @return int
     */
    public function setex($ttl, $val) {
        return $this->RH()->setex($this->_storeKey, $ttl, $val);
    }

    /**
     * 键不存在时设置值
     * @param string/int $val
     * @return bool
     */
    public function setnx($val) {
        return $this->RH()->setnx($this->_storeKey, $val);
    }

    /**
     * 向指定的Key追加字符串
     * @param string $val 要追加的字符串
     * @return bool
     */
    public function append($val) {
        return $this->RH()->append($this->_storeKey, $val);
    }

    /**
     * 获取值
     * @return string
     */
    public function get() {
        return $this->RH()->get($this->_storeKey);
    }

    /**
     * 获取字符串的二进制形式
     * @return string
     */
    public function getBinStr() {
        return $this->__str2bin($this->RH()->get($this->_storeKey));
    }

    /**
     * 获取并设置
     * @param string/int $val
     * @return string
     */
    public function getSet($val) {
        return $this->RH()->getSet($this->_storeKey, $val);
    }

    /**
     * 设置指定字符串指定位的值(0/1)
     * @param int $offset
     * @param int $value 0/1
     * @return int 0 or 1, the value of the bit before it was set.
     */
    public function setBit($offset, $value) {
        return $this->RH()->setBit($this->_storeKey, $offset, $value);
    }

    /**
     * 返回指定字符串中指定位置位的值(0/1)
     * @param int $offset
     * @return int the bit value (0 or 1)
     */
    public function getBit($offset) {
        return $this->RH()->getBit($this->_storeKey, $offset);
    }

    /**
     * 为指定键设置过期时间
     * @param int $ttl
     * @return bool
     */
    public function expire($ttl) {
        return $this->RH()->expire($this->_storeKey, $ttl);
    }

    /**
     * 为指定键设置过期时间
     * @param int $timestamp
     * @return bool
     */
    public function expireAt($timestamp) {
        return $this->RH()->expireAt($this->_storeKey, $timestamp);
    }

    /**
     * 检查存储键是否存在
     * @return bool
     */
    public function exists() {
        return $this->RH()->exists($this->_storeKey);
    }

    /**
     * 删除指定的键
     * @return int Number of keys deleted.
     */
    public function delete() {
        return $this->RH()->delete($this->_storeKey);
    }

    /**
     * 将字符串转化成二进制
     * @param string $str
     * @return string
     */
    private function __str2bin($str) {
        if (!is_string($str)) {
            return '';
        }
        $len = strlen($str);
        $i = 0;
        $temp = '';
        while ($i < $len) {
            $temp .= sprintf('%08b', ord($str[$i++]));
        }
        return $temp;
    }
}
?>