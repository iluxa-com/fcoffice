<?php
/*
 * Step数据SQL模型类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class StepDataSQLModel extends SQLModel {
    /**
     *  服务器组名
     * @var string
     */
    protected $_serverGroup = "main.stat";
    /**
     * 数据表名称
     * @var string
     */
    protected $_tableName = "step_day";
}
?>
