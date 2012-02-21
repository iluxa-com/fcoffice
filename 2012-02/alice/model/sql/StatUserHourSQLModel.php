<?php
/*
 * 时用户统计数据SQL模型类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class StatUserHourSQLModel extends SQLModel {
    /**
     *  服务器组名
     * @var string
     */
    protected $_serverGroup = "main.stat";
    /**
     * 数据表名称
     * @var string
     */
    protected $_tableName = "user_hour";
}
?>
