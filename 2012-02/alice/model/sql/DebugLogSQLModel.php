<?php
/**
 * 调试日志SQL模型类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class DebugLogSQLModel extends SQLModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'main.public';
    /**
     * 表名
     * @var string
     */
    protected $_tableName = 'debug_log';
}
?>