<?php
/**
 * 留言SQL模型类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class LeaveMsgSQLModel extends SQLModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.log';
    /**
     * 表名
     * @var string
     */
    protected $_tableName = 'leave_msg';
}
?>