<?php
/**
 * Memcache类声明文件，用于代码智能提示
 * 
 * @link http://php.net/manual/en/book.memcache.php
 * @author xianlinli@gmail.com
 * @package Alice
 */
class Memcache {
    /**
     * Add an item to the server
     * @param string $key The key that will be associated with the item.
     * @param mixed $var The variable to store. Strings and integers are stored as is, other types are stored serialized.
     * @param int $flag Use MEMCACHE_COMPRESSED to store the item compressed (uses zlib).
     * @param int $expire Expiration time of the item. If it's equal to zero, the item will never expire. You can also use Unix timestamp or a number of seconds starting from current time, but in the latter case the number of seconds may not exceed 2592000 (30 days).
     * @return bool Returns TRUE on success or FALSE on failure. Returns FALSE if such key already exist. For the rest Memcache::add() behaves similarly to Memcache::set().
     */
    public function add($key, $var, $flag = NULL, $expire = NULL) {

    }

    /**
     * Add a memcached server to connection pool
     * @param string $host Point to the host where memcached is listening for connections. This parameter may also specify other transports like unix:///path/to/memcached.sock to use UNIX domain sockets, in this case port must also be set to 0.
     * @param int $port Point to the port where memcached is listening for connections. Set this parameter to 0 when using UNIX domain sockets.
     * @param bool $persistent Controls the use of a persistent connection. Default to TRUE.
     * @param int $weight Number of buckets to create for this server which in turn control its probability of it being selected. The probability is relative to the total weight of all servers.
     * @param int $timeout Value in seconds which will be used for connecting to the daemon. Think twice before changing the default value of 1 second - you can lose all the advantages of caching if your connection is too slow.
     * @param int $retry_interval Controls how often a failed server will be retried, the default value is 15 seconds. Setting this parameter to -1 disables automatic retry. Neither this nor the persistent parameter has any effect when the extension is loaded dynamically via dl().
     * @param bool $status Controls if the server should be flagged as online. Setting this parameter to FALSE and retry_interval to -1 allows a failed server to be kept in the pool so as not to affect the key distribution algorithm. Requests for this server will then failover or fail immediately depending on the memcache.allow_failover setting. Default to TRUE, meaning the server should be considered online.
     * @param callback $failure_callback Allows the user to specify a callback function to run upon encountering an error. The callback is run before failover is attempted. The function takes two parameters, the hostname and port of the failed server.
     * @param int $timeoutms
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function addServer($host, $port = 11211, $persistent = true, $weight = NULL, $timeout = NULL, $retry_interval = 15, $status = true, $failure_callback = NULL, $timeoutms = NULL) {

    }

    /**
     * Close memcached server connection
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function close() {

    }

    /**
     * Open memcached server connection
     * @param string $host Point to the host where memcached is listening for connections. This parameter may also specify other transports like unix:///path/to/memcached.sock to use UNIX domain sockets, in this case port must also be set to 0.
     * @param int $port Point to the port where memcached is listening for connections. Set this parameter to 0 when using UNIX domain sockets.
     * @param int $timeout Value in seconds which will be used for connecting to the daemon. Think twice before changing the default value of 1 second - you can lose all the advantages of caching if your connection is too slow.
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function connect($host, $port = NULL, $timeout = NULL) {

    }

    /**
     * Decrement item's value
     * @param string $key Key of the item do decrement.
     * @param int $value Decrement the item by value.
     * @return int/false Returns item's new value on success or FALSE on failure.
     */
    public function decrement($key, $value = 1) {

    }

    /**
     * Delete item from the server
     * @param string $key The key associated with the item to delete.
     * @param Execution time of the item. If it's equal to zero, the item will be deleted right away whereas if you set it to 30, the item will be deleted in 30 seconds.
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function delete($key, $timeout = NULL) {

    }

    /**
     * Flush all existing items at the server
     * @return bool Return TRUE on success or FALSE on failure.
     */
    public function flush() {

    }

    /**
     * Retrieve item from the server
     * @param string/array $key The key or array of keys to fetch.
     * @param &int/array $flags If present, flags fetched along with the values will be written to this parameter. These flags are the same as the ones given to for example Memcache::set(). The lowest byte of the int is reserved for pecl/memcache internal usage (e.g. to indicate compression and serialization status).
     * @return mixed Returns the value associated with the key or FALSE on failure or if such key was not found.
     */
    public function get($key, &$flags) {

    }

    /**
     * Get statistics from all servers in pool
     * @param string $type The type of statistics to fetch. Valid values are {reset, malloc, maps, cachedump, slabs, items, sizes}. According to the memcached protocol spec these additional arguments "are subject to change for the convenience of memcache developers".
     * @param int $slabid Used in conjunction with type set to cachedump to identify the slab to dump from. The cachedump command ties up the server and is strictly to be used for debugging purposes.
     * @param int $limit Used in conjunction with type set to cachedump to limit the number of entries to dump.
     * @return array/false Returns a two-dimensional associative array of server statistics or FALSE on failure.
     */
    public function getExtendedStats($type, $slabid = NULL, $limit = 100) {

    }

    /**
     * Returns server status
     * @param string $host Point to the host where memcached is listening for connections.
     * @param int $port Point to the port where memcached is listening for connections.
     * @return int Returns a the servers status. 0 if server is failed, non-zero otherwise
     */
    public function getServerStatus($host, $port = 11211) {

    }

    /**
     * Get statistics of the server
     * @param string $type The type of statistics to fetch. Valid values are {reset, malloc, maps, cachedump, slabs, items, sizes}. According to the memcached protocol spec these additional arguments "are subject to change for the convenience of memcache developers".
     * @param int $slabid Used in conjunction with type set to cachedump to identify the slab to dump from. The cachedump command ties up the server and is strictly to be used for debugging purposes.
     * @param int $limit Used in conjunction with type set to cachedump to limit the number of entries to dump.
     * @return array/false  Returns an associative array of server statistics or FALSE on failure.
     */
    public function getStats($type, $slabid = NULL, $limit = 100) {

    }

    /**
     * Return version of the server
     * @return string/false Returns a string of server version number or FALSE on failure.
     */
    public function getVersion() {

    }

    /**
     * Increment item's value
     * @param string $key Key of the item to increment.
     * @param int $value Increment the item by value.
     * @return int/false Returns new items value on success or FALSE on failure.
     */
    public function increment($key, $value = 1) {

    }

    /**
     * Open memcached server persistent connection
     * @param string $host Point to the host where memcached is listening for connections. This parameter may also specify other transports like unix:///path/to/memcached.sock to use UNIX domain sockets, in this case port must also be set to 0.
     * @param int $port Point to the port where memcached is listening for connections. Set this parameter to 0 when using UNIX domain sockets.
     * @param int $timeout you can lose all the advantages of caching if your connection is too slow.
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function pconnect($host, $port = NULL, $timeout = NULL) {

    }

    /**
     * Replace value of the existing item
     * @param string $key The key that will be associated with the item.
     * @param mixed $var The variable to store. Strings and integers are stored as is, other types are stored serialized.
     * @param int $flag Use MEMCACHE_COMPRESSED to store the item compressed (uses zlib).
     * @param int $expire Expiration time of the item. If it's equal to zero, the item will never expire. You can also use Unix timestamp or a number of seconds starting from current time, but in the latter case the number of seconds may not exceed 2592000 (30 days).
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function replace($key, $var, $flag = NULL, $expire = NULL) {

    }

    /**
     * Store data at the server
     * @param string $key The key that will be associated with the item.
     * @param mixed $var The variable to store. Strings and integers are stored as is, other types are stored serialized.
     * @param int $flag Use MEMCACHE_COMPRESSED to store the item compressed (uses zlib).
     * @param int $expire Expiration time of the item. If it's equal to zero, the item will never expire. You can also use Unix timestamp or a number of seconds starting from current time, but in the latter case the number of seconds may not exceed 2592000 (30 days).
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function set($key, $var, $flag = NULL, $expire = NULL) {

    }

    /**
     * Enable automatic compression of large values
     * @param int $threshold Controls the minimum value length before attempting to compress automatically.
     * @param int $min_savings Specifies the minimum amount of savings to actually store the value compressed. The supplied value must be between 0 and 1. Default value is 0.2 giving a minimum 20% compression savings.
     */
    public function setCompressThreshold($threshold, $min_savings = NULL) {

    }

    /**
     * Changes server parameters and status at runtime
     * @param string $host Point to the host where memcached is listening for connections.
     * @param int $port Point to the port where memcached is listening for connections.
     * @param int $timeout Value in seconds which will be used for connecting to the daemon. Think twice before changing the default value of 1 second - you can lose all the advantages of caching if your connection is too slow.
     * @param int $retry_interval Controls how often a failed server will be retried, the default value is 15 seconds. Setting this parameter to -1 disables automatic retry. Neither this nor the persistent parameter has any effect when the extension is loaded dynamically via dl().
     * @param bool $status Controls if the server should be flagged as online. Setting this parameter to FALSE and retry_interval to -1 allows a failed server to be kept in the pool so as not to affect the key distribution algoritm. Requests for this server will then failover or fail immediately depending on the memcache.allow_failover setting. Default to TRUE, meaning the server should be considered online.
     * @param callback $failure_callback Allows the user to specify a callback function to run upon encountering an error. The callback is run before failover is attempted. The function takes two parameters, the hostname and port of the failed server.
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function setServerParams($host, $port = 11211, $timeout = NULL, $retry_interval = false, $status = NULL, $failure_callback = NULL) {

    }
}

define('MEMCACHE_COMPRESSED', 2);
define('MEMCACHE_HAVE_SESSION', 1);
?>