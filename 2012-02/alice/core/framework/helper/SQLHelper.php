<?php
/**
 * SQLHelper类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class SQLHelper {
    /**
     * 数据库名
     * @var string
     */
    private $_databaseName;
    /**
     * 表名
     * @var string
     */
    private $_tableName;
    /**
     * 查询字段
     * @var string
     */
    private $_fields = '*';
    /**
     * 数据数组
     * @var array
     */
    private $_dataArr = array();
    /**
     * where条件数组
     * @var array
     */
    private $_whereArr = array();
    /**
     * group by分组数组
     * @var array
     */
    private $_groupByArr = array();
    /**
     * order by排序数组
     * @var array
     */
    private $_orderByArr = array();
    /**
     * 是否带WITH ROLLUP
     * @var bool
     */
    private $_withRollUp = false;
    /**
     * limit偏移
     * @var int
     */
    private $_offset = 0;
    /**
     * limit行数
     * @var int
     */
    private $_rowCount = NULL;
    /**
     * DB配置数组
     * @var array
     */
    private $_dbConfigArr;
    /**
     * DB辅助类的实例
     * @var MySQLHelper
     */
    private $_dbHelper;
    /**
     * DB驱动名称(MySQLHelper/MySQLiHelper/PDOHelper)
     * @var string
     */
    private $_dbDriver;

    /**
     * 构造函数
     * @param array $configArr 配置数组array($dbConfigArr, $dbDriver)
     */
    public function __construct($configArr) {
        $this->_dbConfigArr = $configArr[0];
        $this->_dbDriver = $configArr[1];
    }

    /**
     * 获取DB辅助类的实例
     * @param bool $changeDatabase 是否需要更改活动数据库
     * @return MySQLHelper
     */
    public function DH($changeDatabase = true) {
        if (!isset($this->_dbHelper)) {
            $this->_dbHelper = new $this->_dbDriver($this->_dbConfigArr);
        }
        if ($changeDatabase) {
            $this->_dbHelper->selectDB($this->_databaseName);
        }
        return $this->_dbHelper;
    }

    /**
     * 设置数据库名
     * @param string $databaseName 数据库名
     * @return object
     */
    public function database($databaseName) {
        $this->_databaseName = $databaseName;
        return $this;
    }

    /**
     * 设置表名
     * @param string $tableName 表名
     * @return object
     */
    public function table($tableName) {
        $this->_tableName = $tableName;
        return $this;
    }

    /**
     * 设置where条件数组
     * @param array $whereArr where条件数组
     * @return object
     */
    public function find($whereArr) {
        $this->_whereArr = $whereArr;
        return $this;
    }

    /**
     * 设置查询字段
     * @param string $fields 查询字段
     * @return object
     */
    public function fields($fields) {
        $this->_fields = $fields;
        return $this;
    }

    /**
     * 设置group by分组数组
     * @param array $groupByArr 分组数组
     * @return object
     */
    public function groupBy($groupByArr) {
        $this->_groupByArr = $groupByArr;
        return $this;
    }

    /**
     * 设置是否带WITH ROLLUP
     * @return object
     */
    public function withRollUp() {
        $this->_withRollUp = true;
        return $this;
    }

    /**
     * 设置order by排序数组
     * @param array $orderByArr 排序数组
     * @return object
     */
    public function orderBy($orderByArr) {
        $this->_orderByArr = $orderByArr;
        return $this;
    }

    /**
     * 设置limit偏移及行数
     * @param int $offset 偏移
     * @param int $rowCount 行数
     * @return object
     */
    public function limit($offset, $rowCount = NULL) {
        if ($rowCount === NULL) {
            $this->_offset = 0;
            $this->_rowCount = $offset;
        } else {
            $this->_offset = $offset;
            $this->_rowCount = $rowCount;
        }
        return $this;
    }

    /**
     * 根据给定的查询类型,构造对应的SQL语句
     * @param string $type 查询类型
     * @return string SQL语句
     */
    private function __makeQuery($type) {
        switch ($type) {
            case 'select-one':
            case 'select-all':
                $sql = 'SELECT ' . $this->_fields . ' FROM `' . $this->_databaseName . '`.`' . $this->_tableName . '`';
                break;
            case 'insert':
                $sql = 'INSERT INTO `' . $this->_databaseName . '`.`' . $this->_tableName . '`';
                break;
            case 'update':
                $sql = 'UPDATE `' . $this->_databaseName . '`.`' . $this->_tableName . '`';
                break;
            case 'delete':
                $sql = 'DELETE FROM `' . $this->_databaseName . '`.`' . $this->_tableName . '`';
                break;
            case 'count':
                $sql = 'SELECT COUNT(' . $this->_fields . ') FROM `' . $this->_databaseName . '`.`' . $this->_tableName . '`';
                break;
            default:
                throw new UserException(UserException::ERROR_SYSTEM, 'UNKNOWN_QUERY_TYPE', $type);
                break;
        }
        if (!empty($this->_dataArr) && $type === 'insert') {// 构造insert字符串
            $keyArr = array();
            $valArr = array();
            foreach ($this->_dataArr as $key => $val) {
                $keyArr[] = '`' . $key . '`';
                $valArr[] = "'" . $val . "'";
            }
            $sql .= ' (' . implode(',', $keyArr) . ') VALUES (' . implode(',', $valArr) . ')';
        }
        if (!empty($this->_dataArr) && $type === 'update') {// 构造update字符串
            $tempArr = array();
            foreach ($this->_dataArr as $key => $val) {
                if (is_array($val)) {
                    switch ($key) {
                        case '+': // 增加
                        case '-': // 减少
                            break;
                        default:
                            throw new UserException(UserException::ERROR_SYSTEM, 'UNKNOWN_UPDATE_KEY', $key);
                    }
                    foreach ($val as $key2 => $val2) {// 构造增减字符串(形如:field=field+1,field=field-2)
                        $tempArr[] = $key2 . "={$key2}{$key}" . $val2;
                    }
                } else {
                    $tempArr[] = $key . "='" . $val . "'";
                }
            }
            $sql .= ' SET ' . implode(',', $tempArr);
        }
        if (!empty($this->_whereArr) && $type !== 'insert') {// 构造where条件字符串
            $tempArr = array();
            foreach ($this->_whereArr as $key => $val) {
                if (is_array($val)) {
                    switch ($key) {
                        case '>':
                        case '>=':
                        case '<':
                        case '<=':
                        case '<>':
                        case '!=':
                        case 'LIKE':
                            break;
                        default:
                            throw new UserException(UserException::ERROR_SYSTEM, 'UNKNOWN_WHERE_KEY', $key);
                    }
                    foreach ($val as $key2 => $val2) { // 构造比较字符串(形如:field>='100',field<'50')
                        if ($key === 'LIKE') {
                            $tempArr[] = $key2 . ' ' . $key . " '" . $val2 . "'";
                        } else {
                            $tempArr[] = $key2 . $key . "'" . $val2 . "'";
                        }
                    }
                } else {
                    $tempArr[] = $key . "='" . $val . "'";
                }
            }
            $sql .= ' WHERE ' . implode(' AND ', $tempArr);
        }
        if ($type === 'select-one' || $type === 'select-all') {
            if (!empty($this->_groupByArr)) { // 构造group分组字符串
                $tempArr = array();
                foreach ($this->_groupByArr as $key => $val) {
                    $tempArr[] = ($val === '') ? $key : $key . ' ' . $val;
                }
                $sql .= ' GROUP BY ' . implode(',', $tempArr);
                if ($this->_withRollUp) {
                    $sql .= ' WITH ROLLUP';
                }
            }
            if (!empty($this->_orderByArr)) {// 构造order by排序字符串
                $tempArr = array();
                foreach ($this->_orderByArr as $key => $val) {
                    $tempArr[] = $key . ' ' . $val;
                }
                $sql .= ' ORDER BY ' . implode(',', $tempArr);
            }
            if ($this->_rowCount !== NULL) {// 构造limit字符串
                $sql .= ' LIMIT ' . $this->_offset . ',' . $this->_rowCount;
            }
        }
        // 重置所有的属性
        $this->_fields = '*';
        $this->_dataArr = array();
        $this->_whereArr = array();
        $this->_groupByArr = array();
        $this->_withRollUp = false;
        $this->_orderByArr = array();
        $this->_offset = 0;
        $this->_rowCount = NULL;
        return $sql;
    }

    /**
     * 获取一条记录
     * @param int $resultType 结果类型(MYSQL_ASSOC/MYSQL_NUM/MYSQL_BOTH/PDO::FETCH_ASSOC/PDO::FETCH_NUM/PDO::FETCH_BOTH)
     * @return array/false 找到记录时返回一个一维数组,否则返回false
     */
    public function getOne($resultType = NULL) {
        $resultType = $resultType === NULL && $this->_dbDriver === 'PDOHelper' ? PDO::FETCH_ASSOC : MYSQL_ASSOC;
        $this->limit(1);
        return $this->DH(false)->getOne($this->__makeQuery('select-one'), $resultType);
    }

    /**
     * 获取多条记录
     * @param int $resultType 结果类型(MYSQL_ASSOC/MYSQL_NUM/MYSQL_BOTH/PDO::FETCH_ASSOC/PDO::FETCH_NUM/PDO::FETCH_BOTH)
     * @return array 找到记录时返回一个二维数组,否则返回空数组
     */
    public function getAll($resultType = NULL) {
        $resultType = $resultType === NULL && $this->_dbDriver === 'PDOHelper' ? PDO::FETCH_ASSOC : MYSQL_ASSOC;
        return $this->DH(false)->getAll($this->__makeQuery('select-all'), $resultType);
    }

    /**
     * 插入记录
     * @param array $dataArr 数据数组
     * @return true/PDOStatement MySQL/MySQLi返回true,PDO返回PDOStatement
     */
    public function insert($dataArr) {
        $this->_dataArr = $dataArr;
        return $this->DH(false)->query($this->__makeQuery('insert'));
    }

    /**
     * 更新记录
     * @param array $dataArr 数据数组
     * @return true/PDOStatement MySQL/MySQLi返回true,PDO返回PDOStatement
     */
    public function update($dataArr) {
        $this->_dataArr = $dataArr;
        return $this->DH(false)->query($this->__makeQuery('update'));
    }

    /**
     * 删除记录
     * @return true/PDOStatement MySQL/MySQLi返回true,PDO返回PDOStatement
     */
    public function delete() {
        return $this->DH(false)->query($this->__makeQuery('delete'));
    }

    /**
     * 获取记录条数
     * @return string
     */
    public function count() {
        return $this->DH(false)->count($this->__makeQuery('count'));
    }
}
?>