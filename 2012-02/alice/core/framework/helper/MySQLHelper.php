<?php
/**
 * MySQLHelper类
 *
 * @link http://php.net/manual/en/book.mysql.php
 * @author xianlinli@gmail.com
 * @package Alice
 */
class MySQLHelper {
    /**
     * 配置数组
     * @var array
     */
    private $_configArr = array();
    /**
     * 连接标识
     * @var int
     */
    private $_linkId;
    /**
     * 事务是否开启
     */
    private $_transStarted = false;

    /**
     * 构造函数
     * @param array $configArr 配置数组array($host, $port, $username, $password, $dbname, $charset)
     */
    public function __construct($configArr) {
        $this->_configArr = array(
            'host' => $configArr[0], // 主机
            'port' => $configArr[1], // 端口
            'username' => $configArr[2], // 用户名
            'password' => $configArr[3], // 密码
            'dbname' => $configArr[4], // 数据库
            'charset' => $configArr[5], // 字符集(注意MySQL当中utf-8需写成utf8)
        );
    }

    /**
     * 建立到数据库服务器的连接
     */
    public function connect() {
        // 连接数据库
        $this->_linkId = mysql_connect($this->_configArr['host'] . ':' . $this->_configArr['port'], $this->_configArr['username'], $this->_configArr['password']) or $this->error('mysql_connect');

        // 设置字符集
        if ($this->_configArr['charset']) {
            $this->query('SET NAMES ' . $this->_configArr['charset']);
        }

        // 设置活动数据库
        if ($this->_configArr['dbname']) {
            $this->selectDB($this->_configArr['dbname']);
        }
    }

    /**
     * 选择指定数据库作为活动数据库
     * @param string $databaseName
     * @return true
     */
    public function selectDB($databaseName) {
        $ret = mysql_select_db($databaseName, $this->_linkId) or $this->error('mysql_select_db');
        return $ret;
    }

    /**
     * 执行指定查询语句，并返回结果集
     * @param string $sql SQL语句
     * @return resource/true
     */
    public function query($sql) {
        // 检查连接是否建立
        if (!isset($this->_linkId)) {
            $this->connect();
        }

        // 检查是否需要添加到事务队列当中
        if (!$this->_transStarted && App::isNeedJoinInTrans($this->_configArr['host'])) {
            $this->start();
            App::joinInTrans($this->_configArr['host'], $this);
        }

        // 注意：赋值运算的优先级高于逻辑or运算，所以总是先执行赋值运算，再进行逻辑or运算
        $result = mysql_query($sql, $this->_linkId) or $this->error('mysql_query', $sql);

        return $result;
    }

    /**
     * 从指定结果集中获取一行作为关联数组、数字数组或二者兼有
     * @param resource $result 结果集
     * @param int $resultType 结果类型(MYSQL_ASSOC/MYSQL_NUM/MYSQL_BOTH)
     * @return array/false 返回根据从结果集取得的行生成的数组，如果没有更多行则返回 FALSE。
     */
    public function fetchArray($result, $resultType = MYSQL_BOTH) {
        return mysql_fetch_array($result, $resultType);
    }

    /**
     * 从指定结果集中获取一行作为关联数组
     * @param resource $result 结果集
     * @return array/false 返回根据从结果集取得的行生成的关联数组，如果没有更多行则返回 FALSE。
     */
    public function fetchAssoc($result) {
        return mysql_fetch_assoc($result);
    }

    /**
     * 从指定结果集中获取一行作为枚举数组
     * @param resource $result 结果集
     * @return array/false 返回根据所取得的行生成的数组，如果没有更多行则返回 FALSE。
     */
    public function fetchRow($result) {
        return mysql_fetch_row($result);
    }

    /**
     * 获取查询记录的条数
     * @param string $sql SQL语句
     * @return string
     */
    public function count($sql) {
        return mysql_result($this->query($sql), 0, 0);
    }

    /**
     * 执行指定查询语句并从结果集中获取一条记录,以一维数组形式返回
     * @param string $sql SQL语句
     * @param int $resultType 结果类型(MYSQL_ASSOC/MYSQL_NUM/MYSQL_BOTH)
     * @return array/false
     */
    public function getOne($sql, $resultType = MYSQL_ASSOC) {
        return $this->fetchArray($this->query($sql), $resultType);
    }

    /**
     * 执行指定查询语句并从结果集中获取所有记录，以二维数组形式返回
     * @param string $sql SQL语句
     * @param int $resultType 结果类型(MYSQL_ASSOC/MYSQL_NUM/MYSQL_BOTH)
     * @return array
     */
    public function getAll($sql, $resultType = MYSQL_ASSOC) {
        $result = $this->query($sql);
        $rowArr = array();
        while ($row = $this->fetchArray($result, $resultType)) {
            $rowArr[] = $row;
        }
        return $rowArr;
    }

    /**
     * 返回给定的连接中上一步INSERT查询中产生的AUTO_INCREMENT的ID号;如果上一查询没有产生AUTO_INCREMENT的值,则返回0
     * @return int
     */
    public function insertID() {
        return mysql_insert_id($this->_linkId);
    }

    /**
     * 开启事务
     */
    public function start() {
        if (!$this->_transStarted) {
            $this->_transStarted = true; // 这一行要放前面,否则会进入死循环
            $this->query('START TRANSACTION');
        }
    }

    /**
     * 提交事务
     */
    public function commit() {
        if ($this->_transStarted) {
            $this->query('COMMIT');
            $this->_transStarted = false;
        }
    }

    /**
     * 事务回滚
     */
    public function rollback() {
        if ($this->_transStarted) {
            $this->query('ROLLBACK');
            $this->_transStarted = false;
        }
    }

    /**
     * 错误处理函数
     * @param string $where
     * @param string $sql
     */
    public function error($where, $sql = '') {
        throw new UserException(UserException::ERROR_MYSQL, 'MYSQL_ERROR', $where, $sql, mysql_error($this->_linkId), mysql_errno($this->_linkId));
    }
}
?>