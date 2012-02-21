<?php
/**
 * Redis类声明文件,用于代码智能提示
 *
 * @link https://github.com/nicolasff/phpredis#readme
 * @link http://redis.io/commands
 * @author xianlinli@gmail.com
 * @package Alice
 */
class Redis {
    const REDIS_NOT_FOUND = 0;
    const REDIS_STRING = 1;
    const REDIS_SET = 2;
    const REDIS_LIST = 3;
    const REDIS_ZSET = 4;
    const REDIS_HASH = 5;
    const ATOMIC = 0;
    const MULTI = 1;
    const PIPELINE = 2;
    const AFTER = "after";
    const BEFORE = "before";

    public function __construct() {

    }

    /**
     * Connects to a Redis instance.
     * @param string $host can be a host, or the path to a unix domain socket
     * @param int $port optional
     * @param float $timeout value in seconds (optional, default is 0 meaning unlimited)
     * @return bool TRUE on success, FALSE on error.
     * @example $redis->connect('127.0.0.1', 6379);<br />
     *           $redis->connect('127.0.0.1'); // port 6379 by default <br />
     *           $redis->connect('127.0.0.1', 6379, 2.5); // 2.5 sec timeout.<br />
     *           $redis->connect('/tmp/redis.sock'); // unix domain socket.
     */
    public function connect($host, $port = 6379, $timeout = 0) {

    }

    /**
     * Disconnects from the Redis instance, except when pconnect is used.
     */
    public function close() {

    }

    /**
     * Check the current connection status
     * @return string +PONG on success. Throws a RedisException object on connectivity error, as described above.
     */
    public function ping() {

    }

    /**
     * Get the value related to the specified key
     * @param string $key
     * @return string/bool If key didn't exist, FALSE is returned. Otherwise, the value related to this key is returned.
     * @example $redis->get('key');
     */
    public function get($key) {

    }

    /**
     * Set the string value in argument as value of the key.
     * @param string $key
     * @param string $value
     * @return bool TRUE if the command is successful.
     * @example $redis->set('key', 'value');
     */
    public function set($key, $value) {

    }

    /**
     * Changes a single bit of a string.
     * @param string $key
     * @param int $offset start from 0
     * @param int $value 1/0
     * @return int 0 or 1, the value of the bit before it was set.
     * @example $redis->setBit('key', 5, 1);
     */
    public function setBit($key, $offset, $value) {

    }

    /**
     * Return a single bit out of a larger string
     * @param string $key
     * @param int $offset
     * @return int 0 or 1
     * @example $redis->getBit('key', 0);
     */
    public function getBit($key, $offset) {

    }

    /**
     * Set the string value in argument as value of the key, with a time to live.
     * @param string $key
     * @param int $ttl
     * @param string $value
     * @return bool TRUE if the command is successful.
     * @example $redis->setex('key', 3600, 'value'); // sets key → value, with 1h TTL.
     */
    public function setex($key, $ttl, $value) {

    }

    /**
     * Set the string value in argument as value of the key if the key doesn't already exist in the database.
     * @param string $key
     * @param string $value
     * @return bool TRUE in case of success, FALSE in case of failure.
     */
    public function setnx($key, $value) {

    }

    /**
     * Sets a value and returns the previous entry at that key.
     * @param string $key
     * @param string $value
     * @return string the previous value located at this key.
     * @example $old = $redis->getSet('x', 'new');
     */
    public function getSet($key, $value) {

    }

    /**
     * Returns a random key.
     * @return string an existing key in redis.
     */
    public function randomKey() {

    }

    /**
     * Renames a key.
     * @param string $srcKey
     * @param string $dstKey
     * @return bool TRUE in case of success, FALSE in case of failure.
     */
    public function renameKey($srcKey, $dstKey) {

    }

    /**
     * Same as rename, but will not replace a key if the destination already exists. This is the same behaviour as setNx.
     * @param string $srcKey
     * @param string $dstKey
     * @return bool  TRUE in case of success, FALSE in case of failure.
     */
    public function renameNx($srcKey, $dstKey) {

    }

    /**
     * Get the values of all the specified keys. If one or more keys dont exist, the array will contain FALSE at the position of the key.
     * @param array $keyArr Array containing the list of the keys
     * @return array Array containing the values related to keys in argument
     * @example $redis->getMultiple(array('key0', 'key1', 'key5')); // array(`FALSE`, 'value2', `FALSE`);
     */
    public function getMultiple($keyArr) {

    }

    /**
     * Verify if the specified key exists.
     * @param string $key
     * @return bool If the key exists, return TRUE, otherwise return FALSE.
     */
    public function exists($key) {

    }

    /**
     * Remove specified keys
     * @param string/array $key An array of keys, or an undefined number of parameters, each a key: key1 key2 key3 ... keyN
     * @return int Number of keys deleted.
     * @example $redis->delete('key1', 'key2');<br />
     *           $redis->delete(array('key3', 'key4'));
     */
    public function delete($key) {

    }

    /**
     * Increment the number stored at key by one. If the second argument is filled, it will be used as the integer value of the increment.
     * @param string $key
     * @return int the new value
     * @example $redis->incr('key1');
     */
    public function incr($key) {

    }

    /**
     * Increment the number stored at key by one. If the second argument is filled, it will be used as the integer value of the increment.
     * @param string $key
     * @param int $value
     * @return int the new value
     * @example $redis->incrBy('key1', 10);
     */
    public function incrBy($key, $value) {

    }

    /**
     * Decrement the number stored at key by one. If the second argument is filled, it will be used as the integer value of the decrement.
     * @param string $key
     * @return int the new value
     * @example $redis->decr('key1');
     */
    public function decr($key) {

    }

    /**
     * Decrement the number stored at key by one. If the second argument is filled, it will be used as the integer value of the decrement.
     * @param string $key
     * @param int $value
     * @return int the new value
     * @example $redis->decrBy('key1', 10);
     */
    public function decrBy($key, $value) {

    }

    /**
     * Returns the type of data pointed by a given key.
     * @param string $key key
     * @return mixed Depending on the type of the data pointed by the key, this method will return the following value:<br />
     *          string: Redis::REDIS_STRING<br />
     *          set: Redis::REDIS_SET<br />
     *          list: Redis::REDIS_LIST<br />
     *          zset: Redis::REDIS_ZSET<br />
     *          hash: Redis::REDIS_HASH<br />
     *          other: Redis::REDIS_NOT_FOUND
     * @example $redis->type('key');
     */
    public function type($key) {

    }

    /**
     * Append specified string to the string stored in specified key.
     * @param string $key
     * @param string $value
     * @return int Size of the value after the append
     */
    public function append($key, $value) {

    }

    /**
     * Return a substring of a larger string
     * @param string $key
     * @param int $start
     * @param int $end
     * @return string the substring
     */
    public function substr($key, $start, $end) {

    }

    /**
     * Get the length of a string value.
     * @param string $key
     * @return int
     */
    public function strlen($key) {

    }

    /**
     * Returns the keys that match a certain pattern.
     * @param string $pattern using '*' as a wildcard.
     * @return array Array of STRING: The keys that match a certain pattern.
     * @example $allKeys = $redis->getKeys('*');    // all keys will match this.<br />
     *           $keyWithUserPrefix = $redis->getKeys('user*');
     */
    public function getKeys($pattern) {

    }

    /**
     * sort
     * @param string $key
     * @param array $options
     * @return array An array of values, or a number corresponding to the number of elements stored if that was used.
     * @example $redis->sort('s');<br />
     *           $redis->sort('s', array('sort' => 'desc'));<br />
     *           $redis->sort('s', array('sort' => 'asc', 'store' => 'out'));
     */
    public function sort($key, $options) {

    }

    public function sortAsc() {

    }

    public function sortAscAlpha() {

    }

    public function sortDesc() {

    }

    public function sortDescAlpha() {

    }

    /**
     * Adds the string value to the head (left) of the list. Creates the list if the key didn't exist. If the key exists and is not a list, FALSE is returned.
     * @param string $key
     * @param string $value
     * @return int The new length of the list in case of success, FALSE in case of Failure.
     */
    public function lPush($key, $value) {

    }

    /**
     * Adds the string value to the tail (right) of the list. Creates the list if the key didn't exist. If the key exists and is not a list, FALSE is returned.
     * @param string $key
     * @param string $value
     * @return int The new length of the list in case of success, FALSE in case of Failure.
     */
    public function rPush($key, $value) {

    }

    /**
     * Adds the string value to the head (left) of the list if the list exists.
     * @param string $key
     * @param string $value
     * @return int The new length of the list in case of success, FALSE in case of Failure.
     */
    public function lPushx($key, $value) {

    }

    /**
     * Adds the string value to the tail (right) of the list if the ist exists. FALSE in case of Failure.
     * @param string $key
     * @param string $value
     * @return int The new length of the list in case of success, FALSE in case of Failure.
     */
    public function rPushx($key, $value) {

    }

    /**
     * Return and remove the first element of the list.
     * @param string $key
     * @return string if command executed successfully BOOL FALSE in case of failure (empty list)
     */
    public function lPop($key) {

    }

    /**
     * Returns and removes the last element of the list.
     * @param string $key
     * @return string if command executed successfully BOOL FALSE in case of failure (empty list)
     */
    public function rPop($key) {

    }

    /**
     * Is a blocking lPop(rPop) primitive. If at least one of the lists contains at least one element, the element will be popped from the head of the list and returned to the caller. Il all the list identified by the keys passed in arguments are empty, blPop will block during the specified timeout until an element is pushed to one of those lists. This element will be popped.
     * @param array $keyArr Array containing the keys of the lists INTEGER Timeout Or STRING Key1 STRING Key2 STRING Key3 ... STRING Keyn INTEGER Timeout
     * @return array
     */
    public function blPop($keyArr) {

    }

    public function brPop() {

    }

    /**
     * Returns the size of a list identified by Key. If the list didn't exist or is empty, the command returns 0. If the data type identified by Key is not a list, the command return FALSE.
     * @param string $key
     * @return int/bool The size of the list identified by Key exists.BOOL FALSE if the data type identified by Key is not list
     */
    public function lSize($key) {

    }

    /**
     * Removes the first count occurences of the value element from the list. If count is zero, all the matching elements are removed. If count is negative, elements are removed from tail to head.
     * @param string $key
     * @param string $value
     * @param int $count
     * @return int/bool LONG the number of elements to remove.BOOL FALSE if the value identified by key is not a list.
     */
    public function lRemove($key, $value, $count) {

    }

    /**
     * Trims an existing list so that it will contain only a specified range of elements.
     * @param string $key
     * @param int $start
     * @param int $stop
     * @return bool return FALSE if the key identify a non-list value.
     */
    public function listTrim($key, $start, $stop) {

    }

    /**
     * Return the specified element of the list stored at the specified key. 0 the first element, 1 the second ... -1 the last element, -2 the penultimate ... Return FALSE in case of a bad index or a key that doesn't point to a list.
     * @param string $key
     * @param int $index
     * @return string/bool String the element at this index.Bool FALSE if the key identifies a non-string data type, or no value corresponds to this index in the list Key.
     */
    public function lGet($key, $index) {

    }

    /**
     * Returns the specified elements of the list stored at the specified key in the range [start, end]. start and stop are interpretated as indices: 0 the first element, 1 the second ... -1 the last element, -2 the penultimate ...
     * @param string $key
     * @param int $start
     * @param int $end
     * @return array Array containing the values in specified range.
     */
    public function lGetRange($key, $start, $end) {

    }

    /**
     * Set the list at index with the new value.
     * @param string $key
     * @param int $index
     * @param string $value
     * @return bool TRUE if the new value is setted. FALSE if the index is out of range, or data type identified by key is not a list.
     */
    public function lSet($key, $index, $value) {

    }

    /**
     * Insert value in the list before or after the pivot value. the parameter options specify the position of the insert (before or after). If the list didn't exists, or the pivot didn't exists, the value is not inserted.
     * @param string $key
     * @param int $position Redis::BEFORE | Redis::AFTER
     * @param string $pivot
     * @param string $value
     * @return int The number of the elements in the list, -1 if the pivot didn't exists.
     */
    public function lInsert($key, $position, $pivot, $value) {

    }

    /**
     * Adds a value to the set value stored at key. If this value is already in the set, FALSE is returned.
     * @param string $key
     * @param string $value
     * @return bool TRUE if value didn't exist and was added successfully, FALSE if the value is already present.
     */
    public function sAdd($key, $value) {

    }

    /**
     * Returns the cardinality of the set identified by key.
     * @param string $key
     * @return int the cardinality of the set identified by key, 0 if the set doesn't exist.
     */
    public function sSize($key) {

    }

    /**
     * Removes the specified member from the set value stored at key.
     * @param string $key
     * @param string $member
     * @return bool TRUE if the member was present in the set, FALSE if it didn't.
     */
    public function sRemove($key, $member) {

    }

    /**
     * Moves the specified member from the set at srcKey to the set at dstKey.
     * @param string $srcKey
     * @param string $dstKey
     * @param string $member
     * @return bool If the operation is successful, return TRUE. If the srcKey and/or dstKey didn't exist, and/or the member didn't exist in srcKey, FALSE is returned.
     */
    public function sMove($srcKey, $dstKey, $member) {

    }

    /**
     * Removes and returns a random element from the set value at Key.
     * @param string $key
     * @return string/bool String "popped" value.Bool FALSE if set identified by key is empty or doesn't exist.
     */
    public function sPop($key) {

    }

    /**
     * Returns a random element from the set value at Key, without removing it.
     * @param string $key
     * @return string/bool String value from the set.Bool FALSE if set identified by key is empty or doesn't exist.
     */
    public function sRandMember($key) {

    }

    /**
     * Checks if value is a member of the set stored at the key key.
     * @param string $key
     * @param string $value
     * @return bool TRUE if value is a member of the set at key key, FALSE otherwise.
     */
    public function sContains($key, $value) {

    }

    /**
     * Returns the contents of a set.
     * @param string $key
     * @return array An array of elements, the contents of the set.
     * @example $redis->sMembers('s');
     */
    public function sMembers($key) {

    }

    /**
     * Returns the members of a set resulting from the intersection of all the sets held at the specified keys. If just a single key is specified, then this command produces the members of this set. If one of the keys is missing, FALSE is returned.
     * @param ... key1, key2, keyN: keys identifying the different sets on which we will apply the intersection.
     * @return array Array, contain the result of the intersection between those keys. If the intersection beteen the different sets is empty, the return value will be empty array.
     * @example $redis->sInter('key1', 'key2', 'key3');
     */
    public function sInter() {

    }

    /**
     * Performs a sInter command and stores the result in a new set.
     * @param string $dstKey the key to store the diff into.
     * @param ... key1, key2... keyN. key1..keyN are intersected as in sInter.
     * @return int The cardinality of the resulting set, or FALSE in case of a missing key.
     * @example $redis->sInterStore('dst', 's0', 's1');
     */
    public function sInterStore() {

    }

    /**
     * Performs the union between N sets and returns it.
     * @param ... key1, key2, ... , keyN: Any number of keys corresponding to sets in redis.
     * @return array Array of strings: The union of all these sets.
     * @example $redis->sUnion('s0', 's1', 's2');
     */
    public function sUnion() {

    }

    /**
     * Performs the same action as sUnion, but stores the result in the first key
     * @param string $dstKey Key: dstkey, the key to store the diff into.
     * @param ... key1, key2, ... , keyN: Any number of keys corresponding to sets in redis.
     * @return int The cardinality of the resulting set, or FALSE in case of a missing key.
     * @example $redis->sUnionStore('dst', 's0', 's1', 's2');
     */
    public function sUnionStore($dstKey) {

    }

    /**
     * Performs the difference between N sets and returns it.
     * @param ... key1, key2, ... , keyN: Any number of keys corresponding to sets in redis.
     * @return array The difference of the first set will all the others.
     * @example $redis->sDiff('s0', 's1', 's2');
     */
    public function sDiff() {

    }

    /**
     * Performs the same action as sDiff, but stores the result in the first key
     * @param string $dstKey dstkey, the key to store the diff into.
     * @param ... key1, key2, ... , keyN: Any number of keys corresponding to sets in redis
     * @return int The cardinality of the resulting set, or FALSE in case of a missing key.
     * @example $redis->sDiffStore('dst', 's0', 's1', 's2');
     */
    public function sDiffStore($dstKey) {

    }

    /**
     * Sets an expiration date (a timeout) on an item.
     * @param string $key The key that will disappear.
     * @param int $ttl The key's remaining Time To Live, in seconds.
     * @return bool TRUE in case of success, FALSE in case of failure.
     */
    public function setTimeout($key, $ttl) {

    }

    /**
     * Performs a synchronous save.
     * @return bool TRUE in case of success, FALSE in case of failure. If a save is already running, this command will fail and return FALSE.
     * @example $redis->save();
     */
    public function save() {

    }

    /**
     * Performs a background save.
     * @return bool TRUE in case of success, FALSE in case of failure. If a save is already running, this command will fail and return FALSE.
     * @example $redis->bgSave();
     */
    public function bgSave() {

    }

    /**
     * Returns the timestamp of the last disk save.
     * @return int timestamp
     * @example $redis->lastSave();
     */
    public function lastSave() {

    }

    /**
     * Removes all entries from the current database.
     * @return bool Always TRUE.
     * @example $redis->flushDB();
     */
    public function flushDB() {

    }

    /**
     * Removes all entries from all databases.
     * @return bool Always TRUE.
     * @example $redis->flushAll();
     */
    public function flushAll() {

    }

    /**
     * Returns the current database's size.
     * @return int DB size, in number of keys.
     * @example $count = $redis->dbSize();<br />
     *           echo "Redis has $count keys\n";
     */
    public function dbSize() {

    }

    /**
     * Authenticate the connection using a password. Warning: The password is sent in plain-text over the network.
     * @param string $password
     * @return bool TRUE if the connection is authenticated, FALSE otherwise.
     * @example $redis->auth('foobared');
     */
    public function auth($password) {

    }

    /**
     * Returns the time to live left for a given key, in seconds. If the key doesn't exist, FALSE is returned.
     * @param string $key
     * @return int/bool Long, the time left to live in seconds.
     * @example $redis->ttl('key');
     */
    public function ttl($key) {

    }

    /**
     * Remove the expiration timer from a key.
     * @param string $key key
     * @return bool TRUE if a timeout was removed, FALSE if the key didn’t exist or didn’t have an expiration timer.
     * @example $redis->persist('key');
     */
    public function persist($key) {

    }

    /**
     * Returns an associative array of strings and integers, with the following keys:
     * redis_version<br />
     * arch_bits<br />
     * uptime_in_seconds<br />
     * uptime_in_days<br />
     * connected_clients<br />
     * connected_slaves<br />
     * used_memory<br />
     * changes_since_last_save<br />
     * bgsave_in_progress<br />
     * last_save_time<br />
     * total_connections_received<br />
     * total_commands_processed<br />
     * role<br />
     */
    public function info() {

    }

    /**
     * Switches to a given database.
     * @param int $dbindex  the database number to switch to.
     * @return bool TRUE in case of success, FALSE in case of failure.
     * @example $redis->select(1);
     */
    public function select($dbindex) {

    }

    /**
     * Moves a key to a different database.
     * @param string $key the key to move.
     * @param int $dbindex the database number to move the key to.
     * @return bool TRUE in case of success, FALSE in case of failure.
     * @example $redis->move('x', 1) // move to DB 1
     */
    public function move($key, $dbindex) {

    }

    /**
     * Starts the background rewrite of AOF (Append-Only File)
     * @return bool TRUE in case of success, FALSE in case of failure.
     */
    public function bgrewriteaof() {

    }

    /**
     * Changes the slave status
     * @param string $host Either host (string) and port (int), or no parameter to stop being a slave.
     * @param int $port
     * @return bool TRUE in case of success, FALSE in case of failure.
     * @example $redis->slaveof('10.0.1.7',6379);<br />
     *           $redis->salveof();
     */
    public function slaveof($host, $port) {

    }

    /**
     * Sets multiple key-value pairs in one atomic command
     * @param array $arr array(key => value, ...)
     * @return bool Bool TRUE in case of success, FALSE in case of failure.
     */
    public function mset($arr) {

    }

    /**
     * Sets multiple key-value pairs in one atomic command
     * @param array $arr array(key => value, ...)
     * @return bool Bool returns TRUE if all the keys were set
     */
    public function msetnx($arr) {

    }

    /**
     * Pops a value from the tail of a list, and pushes it to the front of another list. Also return this value.
     * @param string $srcKey
     * @param string $dstKey
     * @return STRING The element that was moved in case of success, FALSE in case of failure.
     */
    public function rpoplpush($srcKey, $dstKey) {

    }

    /**
     * Adds the specified member with a given score to the sorted set stored at key.
     * @param string $key
     * @param float $score
     * @param string $value
     * @return int 1 if the element is added. 0 otherwise.
     * @example $redis->zAdd('key', 1, 'val1');
     */
    public function zAdd($key, $score, $value) {

    }

    /**
     * Deletes a specified member from the ordered set.
     * @param string $key
     * @param string $member
     * @return int 1 on success, 0 on failure.
     * @example $redis->zDelete('key', 'val2');
     */
    public function zDelete($key, $member) {

    }

    /**
     * Returns a range of elements from the ordered set stored at the specified key, with values in the range [start, end]. start and stop are interpreted as zero-based indices: 0 the first element, 1 the second ... -1 the last element, -2 the penultimate ...
     * @param int $key
     * @param float $start
     * @param float $end
     * @param bool $withscores
     * @return array Array containing the value in specified range.
     * @example $redis->zRange('key1', 0, -1, true);
     */
    public function zRange($key, $start, $end, $withscores = false) {

    }

    /**
     * Returns the elements of the sorted set stored at the specified key in the range [start, end] in reverse order. start and stop are interpretated as zero-based indices: 0 the first element, 1 the second ... -1 the last element, -2 the penultimate ...
     * @param int $key
     * @param float $start
     * @param float $end
     * @param bool $withscores
     * @return array Array containing the values in specified range.
     * @example $redis->zReverseRange('key', 0, -1, true);
     */
    public function zReverseRange($key, $start, $end, $withscores = false) {

    }

    /**
     * Returns the elements of the sorted set stored at the specified key which have scores in the range [start,end]. Adding a parenthesis before start or end excludes it from the range. +inf and -inf are also valid limits.
     * @param string $key
     * @param float $start
     * @param float $end
     * @param array $options array('withscores' => TRUE) or array('limit' => array($offset, $count))
     * @return array Array containing the values in specified range.
     * @example $redis->zRangeByScore('key', 0, 3);<br />
     *           $redis->zRangeByScore('key', 0, 3, array('withscores' => TRUE));<br />
     *           $redis->zRangeByScore('key', 0, 3, array('limit' => array(1, 1)));<br />
     *           $redis->zRangeByScore('key', 0, 3, array('withscores' => TRUE, 'limit' => array(1, 1)));
     */
    public function zRangeByScore($key, $start, $end, $options) {

    }

    /**
     * Returns the number of elements of the sorted set stored at the specified key which have scores in the range [start,end]. Adding a parenthesis before start or end excludes it from the range. +inf and -inf are also valid limits.
     * @param string $key
     * @param float $start
     * @param float $end
     * @return int the size of a corresponding zRangeByScore.
     */
    public function zCount($key, $start, $end) {

    }

    public function zDeleteRangeByScore() {

    }

    /**
     * Returns the cardinality of an ordered set.
     * @param string $key
     * @return int the set's cardinality
     */
    public function zCard($key) {

    }

    /**
     * Returns the score of a given member in the specified sorted set.
     * @param string $key
     * @param string $member
     * @return float the item's score.
     * @example $redis->zScore('key', 'val2');
     */
    public function zScore($key, $member) {

    }

    /**
     * Returns the rank of a given member in the specified sorted set, starting at 0 for the item with the smallest score.
     * @param string $key
     * @param string $member
     * @return int the item's rank.
     * @example $redis->zRank('key', 'one');
     */
    public function zRank($key, $member) {

    }

    /**
     * Returns the rank of a given member in the specified sorted set, starting at 0 for the item with the largest score.
     * @param string $key
     * @param string $member
     * @return int the item's rank.
     * @example $redis->zRevRank('key', 'one');
     */
    public function zRevRank($key, $member) {

    }

    /**
     * Creates an intersection of sorted sets given in second argument. The result of the union will be stored in the sorted set defined by the first argument. The third optionnel argument defines weights to apply to the sorted sets in input. In this case, the weights will be multiplied by the score of each element in the sorted set before applying the aggregation. The forth argument defines the AGGREGATE option which specify how the results of the union are aggregated.
     * @param string $keyOutput
     * @param array $arrayZSetKeys
     * @param array $arrayWeights
     * @return int The number of values in the new sorted set.
     * @example $redis->zInter('ko3', array('k1', 'k2'), array(5, 1));
     */
    public function zInter($keyOutput, $arrayZSetKeys, $arrayWeights) {

    }

    /**
     * Creates an union of sorted sets given in second argument. The result of the union will be stored in the sorted set defined by the first argument. The third optionnel argument defines weights to apply to the sorted sets in input. In this case, the weights will be multiplied by the score of each element in the sorted set before applying the aggregation. The forth argument defines the AGGREGATE option which specify how the results of the union are aggregated.
     * @param string $keyOutput
     * @param array $arrayZSetKeys
     * @param array $arrayWeights
     * @return int The number of values in the new sorted set.
     */
    public function zUnion($keyOutput, $arrayZSetKeys, $arrayWeights) {

    }

    /**
     * Increments the score of a member from a sorted set by a given amount.
     * @param string $key
     * @param float $value (double) value that will be added to the member's score
     * @param string $member
     * @return float the new value
     */
    public function zIncrBy($key, $value, $member) {

    }

    /**
     * Sets an expiration date (a timestamp) on an item.
     * @param string $key The key that will disappear.
     * @param int $timestamp Unix timestamp. The key's date of death, in seconds from Epoch time.
     * @return bool TRUE in case of success, FALSE in case of failure.
     */
    public function expireAt($key, $timestamp) {

    }

    /**
     * Gets a value from the hash stored at key. If the hash table doesn't exist, or the key doesn't exist, FALSE is returned.
     * @param string $key
     * @param string $hashKey
     * @return string/bool STRING The value, if the command executed successfully BOOL FALSE in case of failure
     * @example $redis->hGet('h', 'key1');
     */
    public function hGet($key, $hashKey) {

    }

    /**
     * Adds a value to the hash stored at key. If this value is already in the hash, FALSE is returned.
     * @param string $key
     * @param string $hashKey
     * @param string $value
     * @return int 1 if value didn't exist and was added successfully, 0 if the value was already present and was replaced, FALSE if there was an error.
     * @example $redis->hSet('h', 'key1', 'hello');
     */
    public function hSet($key, $hashKey, $value) {

    }

    /**
     * Adds a value to the hash stored at key only if this field isn't already in the hash.
     * @param string $key
     * @param string $hashKey
     * @param strint/int $value
     * @return bool TRUE if the field was set, FALSE if it was already present.
     * @example $redis->hSetNx('h', 'key1', 'hello');
     */
    public function hSetNx($key, $hashKey, $value) {

    }

    /**
     * Removes a value from the hash stored at key. If the hash table doesn't exist, or the key doesn't exist, FALSE is returned.
     * @param string $key
     * @param string $hashKey
     * @return bool TRUE in case of success, FALSE in case of failure.
     */
    public function hDel($key, $hashKey) {

    }

    /**
     * Returns the length of a hash, in number of items
     * @param string $key
     * @return int LONG the number of items in a hash, FALSE if the key doesn't exist or isn't a hash.
     * @example $redis->hLen('h');
     */
    public function hLen($key) {

    }

    /**
     * Returns the keys in a hash, as an array of strings.
     * @param string $key
     * @return array An array of elements, the keys of the hash. This works like PHP's array_keys().
     * @example $redis->hKeys('h');
     */
    public function hKeys($key) {

    }

    /**
     * Returns the values in a hash, as an array of strings.
     * @param string $key
     * @return array An array of elements, the values of the hash. This works like PHP's array_values().
     * @example $redis->hVals('h');
     */
    public function hVals($key) {

    }

    /**
     * Returns the whole hash, as an array of strings indexed by strings.
     * @param string $key
     * @return array An array of elements, the contents of the hash.
     * @example $redis->hGetAll('h');
     */
    public function hGetAll($key) {

    }

    /**
     * Verify if the specified member exists in a key.
     * @param string $key
     * @param string $memberKey
     * @return bool If the member exists in the hash table, return TRUE, otherwise return FALSE.
     * @example $redis->hExists('h', 'a');
     */
    public function hExists($key, $memberKey) {

    }

    /**
     * Increments the value of a member from a hash by a given amount.
     * @param string $key
     * @param string $memeber
     * @param int $value value that will be added to the member's value
     * @return int the new value
     * @example $redis->hIncrBy('h', 'x', 2);
     */
    public function hIncrBy($key, $memeber, $value) {

    }

    /**
     * Fills in a whole hash. Non-string values are converted to string, using the standard (string) cast. NULL values are stored as empty strings.
     * @param string $key
     * @param array $members key → value array
     * @return bool
     * @example $redis->hMset('user:1', array('name' => 'Joe', 'salary' => 2000));
     */
    public function hMset($key, $members) {

    }

    /**
     * Retirieve the values associated to the specified fields in the hash.
     * @param string $key
     * @param array $memberKeys
     * @return array An array of elements, the values of the specified fields in the hash, with the hash keys as array keys.
     * @example $redis->hMget('h', array('field1', 'field2'));
     */
    public function hMget($key, $memberKeys) {

    }

    /**
     * Enter transactional mode.
     * @param int $mode Redis::MULTI/Redis::PIPELINE
     * @return Redis returns the Redis instance and enters multi-mode. Once in multi-mode, all subsequent method calls return the same object until exec() is called.
     */
    public function multi($mode) {

    }

    public function discard() {

    }

    /**
     * @return Redis
     */
    public function exec() {

    }

    public function pipeline() {

    }

    /**
     * Watches a key for modifications by another client. If the key is modified between WATCH and EXEC, the MULTI/EXEC transaction will fail (return FALSE). unwatch cancels all the watching of all keys by this client.
     * @param string $key
     */
    public function watch($key) {

    }

    /**
     * Watches a key for modifications by another client. If the key is modified between WATCH and EXEC, the MULTI/EXEC transaction will fail (return FALSE). unwatch cancels all the watching of all keys by this client.
     */
    public function unwatch() {

    }

    /**
     * Publish messages to channels. Warning: this function will probably change in the future.
     * @param string $channel a channel to publish to
     * @param string $message
     */
    public function publish($channel, $message) {

    }

    /**
     * Subscribe to channels. Warning: this function will probably change in the future.
     * @param array $channels an array of channels to subscribe to
     * @param string/array $callback either a string or an array($instance, 'method_name'). The callback function receives 3 parameters: the redis instance, the channel name, and the message.
     * @example $redis->subscribe(array('chan-1', 'chan-2', 'chan-3'), 'f'); // subscribe to 3 chans
     */
    public function subscribe($channels, $callback) {

    }

    public function unsubscribe() {

    }

    /**
     * same as connect
     * @param string $host
     * @param int $port
     * @param float $timeout
     * @return bool TRUE on success, FALSE on error.
     */
    public function open($hos, $port = 6379, $timeout = 0) {

    }

    /**
     * Return the length of the List value at key
     * @param string $key
     * @return int
     */
    public function lLen() {

    }

    /**
     * Returns the contents of a set.
     * @param string $key
     * @return array An array of elements, the contents of the set.
     */
    public function sGetMembers($key) {

    }

    /**
     * Get the values of all the specified keys. If one or more keys dont exist, the array will contain FALSE at the position of the key.
     * @param array
     * @return array
     */
    public function mget($keyArr) {

    }

    /**
     * Sets an expiration date (a timeout) on an item.
     * @param string $key The key that will disappear.
     * @param int $ttl The key's remaining Time To Live, in seconds.
     * @return bool TRUE in case of success, FALSE in case of failure.
     */
    public function expire($key, $ttl) {

    }

    public function zunionstore() {

    }

    public function zinterstore() {

    }

    /**
     * Deletes a specified member from the ordered set.
     * @param string $key
     * @param string $member
     * @return int 1 on success, 0 on failure.
     * @example $redis->zRemove('key', 'val2');
     */
    public function zRemove($key, $member) {

    }

    public function zRemoveRangeByScore() {

    }

    /**
     * Returns the cardinality of an ordered set.
     * @param string $key
     * @return int the set's cardinality
     */
    public function zSize($key) {

    }
}

/**
 * RedisException
 */
class RedisException extends Exception {

}
?>