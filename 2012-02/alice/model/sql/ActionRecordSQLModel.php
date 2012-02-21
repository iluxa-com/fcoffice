<?php
/*
 * 动作记录SQL模型类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class ActionRecordSQLModel extends SQLModel {
    /**
     *  服务器组名
     * @var string
     */
    protected $_serverGroup = "main.stat";
    /**
     * 数据表名称
     * @var string
     */
    protected $_tableName = "action_record";
}
?>
