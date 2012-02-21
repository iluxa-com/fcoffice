<?php
/**
 * SQL模型类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
abstract class SQLModel {
    /**
     * DB驱动名称(MySQLHelper/MySQLiHelper/PDOHelper)
     * @var string
     */
    private $_dbDriver = 'PDOHelper';
    /**
     * SQL驱动名称(SQLHelper)
     * @var string
     */
    private $_sqlDriver = 'SQLHelper';
    /**
     * SQL辅助类的实例数组
     * @var array
     */
    private static $_sqlHelper = array();
    /**
     * 是否启用多表
     * @var bool
     */
    protected $_enableMultiTable = false;
    /**
     * 轮转ID
     * @var int
     */
    private $_circleId = 0;

    /**
     * 构造函数
     */
    public function __construct() {
        list($serverType, $moduleName) = explode('.', $this->_serverGroup);
        switch ($serverType) {
            case 'main':
                // 设置轮转ID(固定为0)
                $this->_circleId = 0;
                break;
            case 'circle':
                // 设置轮转ID(取用户ID)
                $args = func_get_args();
                if (empty($args) || $args[0] === NULL) {
                    $args[0] = App::get('user_id');
                }
                $this->_circleId = $args[0];
                if ($this->_enableMultiTable) { // 启用多表时,重写表名
                    $this->_tableName .= sprintf('_%03d', $this->_circleId / 1000000);
                }
                break;
            default:
                throw new UserException(UserException::ERROR_SYSTEM, 'UNDEFINED_SERVER_GROUP', $this->_serverGroup);
                break;
        }
    }

    /**
     * 获取SQL辅助类的实例
     * @return SQLHelper
     */
    public function SH() {
        $configArr = App::getServerConfig('MySQLServer', $this->_serverGroup, $this->_circleId);
        $key = $configArr[0] . ':' . $configArr[1];
        if (!isset(self::$_sqlHelper[$key])) {
            self::$_sqlHelper[$key] = App::getInst($this->_sqlDriver, array($configArr, $this->_dbDriver), false, $key);
        }
        self::$_sqlHelper[$key]->database($configArr[4]); // 更改数据库名
        self::$_sqlHelper[$key]->table($this->_tableName); // 更改表名
        return self::$_sqlHelper[$key];
    }
}
?>