<?php
/**
 * MemcacheHelper类
 *
 * @link http://php.net/manual/en/book.memcache.php
 * @author xianlinli@gmail.com
 * @package Alice
 */
class MemcacheHelper {
    /**
     * 配置数组
     * @var array
     */
    private $_configArr = array();
    /**
     * Memcache的实例
     * @var Memcache
     */
    private $_m;
    /**
     * 是否启用压缩(NULL/MEMCACHE_COMPRESSED)
     * @var mixed
     */
    private $_compression = NULL;
    /**
     * 默认过期时间
     * @var int
     */
    private $_defaultExpiration = 600;

    /**
     * 构造函数
     * @param array $configArr 配置数组array($host, $port)
     */
    public function __construct($configArr) {
        $this->_configArr = array(
            'host' => $configArr[0],
            'port' => $configArr[1],
        );
    }

    /**
     * 获取Memcache的实例
     * @return Memcache
     */
    public function getConn() {
        if (!isset($this->_m)) {
            // 检查扩展模块是否加载
            if (!extension_loaded('memcache')) {
                throw new UserException(UserException::ERROR_EXTENSION_NOT_LOADED, 'EXTENSION_NOT_LOADED', 'memcache');
            }
            // 创建Memcache的实例
            $this->_m = new Memcache();
            $ret = $this->_m->connect($this->_configArr['host'], $this->_configArr['port']);
            if ($ret === false) {
                throw new UserException(UserException::ERROR_MEMCACHE, 'CANNOT_CONNECT_MEMCACHE_SERVER');
            }
        }
        return $this->_m;
    }

    /**
     * Add an item to the server
     * @param string $key The key that will be associated with the item.
     * @param mixed $val The variable to store. Strings and integers are stored as is, other types are stored serialized.
     * @param int $expiration 默认为NULL,表示采用类中设定的默认值作为过期时间,否则采用实际给定的值作为过期时间
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function add($key, $val, $expiration = NULL) {
        if ($expiration === NULL) {
            $expiration = $this->_defaultExpiration;
        }
        return $this->getConn()->add($key, $val, $this->_compression, $expiration);
    }

    /**
     * Append data to an existing item[不支持]
     * @param string $key The key under which to store the value.
     * @param string $val The string to append.
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function append($key, $val) {
        throw new UserException(UserException::ERROR_MEMCACHE, 'UNSUPPORTED_FUNCTION', 'append');
    }

    /**
     * Compare and swap an item[不支持]
     * @param float $casToken Unique value associated with the existing item. Generated by memcache.
     * @param string $key The key under which to store the value.
     * @param mixed $val The value to store.
     * @param int $expiration 默认为NULL,表示采用类中设定的默认值作为过期时间,否则采用实际给定的值作为过期时间
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function cas($casToken, $key, $val, $expiration = NULL) {
        throw new UserException(UserException::ERROR_MEMCACHE, 'UNSUPPORTED_FUNCTION', 'cas');
    }

    /**
     * Decrement item's value
     * @param string $key Key of the item do decrement.
     * @param int $offset Decrement the item by value.
     * @return int/false Returns item's new value on success or FALSE on failure.
     */
    public function decrement($key, $offset = 1) {
        return $this->getConn()->decrement($key, $offset);
    }

    /**
     * Delete item from the server
     * @param string $key The key associated with the item to delete.
     * @param int $time Execution time of the item. If it's equal to zero, the item will be deleted right away whereas if you set it to 30, the item will be deleted in 30 seconds.
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function delete($key, $time = 0) {
        return $this->getConn()->delete($key, $time);
    }

    /**
     * Retrieve item from the server[参数不同于MemcachedHelper::get()方法]
     * @param string/array $key The key or array of keys to fetch.
     * @param &int/array $flags If present, flags fetched along with the values will be written to this parameter.
     * @return bool Returns the string associated with the key or FALSE on failure or if such key was not found.
     */
    public function get($key, &$flags = NULL) {
        $numArgs = func_num_args();
        if ($numArgs == 1) {
            return $this->getConn()->get($key);
        } else {
            return $this->getConn()->get($key, $flags);
        }
    }

    /**
     * Retrieve multiple items[不支持]
     * @param array $keys Array of keys to retrieve.
     * @param array $casTokens The variable to store the CAS tokens for the found items.
     * @param int $flags The flags for the get operation.
     * @return bool Returns the array of found items or FALSE on failure.
     */
    public function getMulti($keys, &$casTokens, $flags = Memcached::GET_PRESERVE_ORDER) {
        throw new UserException(UserException::ERROR_MEMCACHE, 'UNSUPPORTED_FUNCTION', 'getMulti');
    }

    /**
     * Increment item's value
     * @param string $key Key of the item to increment.
     * @param int $offset Increment the item by offset.
     * @return int/false Returns new items value on success or FALSE on failure.
     */
    public function increment($key, $offset = 1) {
        return $this->getConn()->increment($key, $offset);
    }

    /**
     * Prepend data to an existing item[不支持]
     * @param string $key The key of the item to prepend the data to.
     * @param string $val The string to prepend.
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function prepend($key, $val) {
        throw new UserException(UserException::ERROR_MEMCACHE, 'UNSUPPORTED_FUNCTION', 'prepend');
    }

    /**
     * Replace value of the existing item
     * @param string $key The key that will be associated with the item.
     * @param mixed $val The variable to store. Strings and integers are stored as is, other types are stored serialized.
     * @param int $expiration 默认为NULL,表示采用类中设定的默认值作为过期时间,否则采用实际给定的值作为过期时间
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function replace($key, $val, $expiration = NULL) {
        if ($expiration === NULL) {
            $expiration = $this->_defaultExpiration;
        }
        return $this->getConn()->replace($key, $val, $this->_compression, $expiration);
    }

    /**
     * Store data at the server
     * @param string $key The key that will be associated with the item.
     * @param mixed $val The variable to store. Strings and integers are stored as is, other types are stored serialized.
     * @param int $expiration 默认为NULL,表示采用类中设定的默认值作为过期时间,否则采用实际给定的值作为过期时间
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function set($key, $val, $expiration = NULL) {
        if ($expiration === NULL) {
            $expiration = $this->_defaultExpiration;
        }
        return $this->getConn()->set($key, $val, $expiration);
    }

    /**
     * Store multiple items[不支持]
     * @param array $items An array of key/value pairs to store on the server.
     * @param int $expiration 默认为NULL,表示采用类中设定的默认值作为过期时间,否则采用实际给定的值作为过期时间
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function setMulti($items, $expiration = NULL) {
        throw new UserException(UserException::ERROR_MEMCACHE, 'UNSUPPORTED_FUNCTION', 'setMulti');
    }
}
?>