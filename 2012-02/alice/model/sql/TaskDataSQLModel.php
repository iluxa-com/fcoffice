<?php
/**
 * 任务数据SQL模型类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class TaskDataSQLModel extends SQLModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'main.public';
    /**
     * 表名
     * @var string
     */
    protected $_tableName = 'task_data';
}
?>