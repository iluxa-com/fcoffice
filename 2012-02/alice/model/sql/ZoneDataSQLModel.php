<?php
/*
 * 区域数据SQL模型类
 *
 * @author wangr@gmail.com
 * @package Alice
 */
class ZoneDataSQLModel extends SQLModel {
    /**
     *  服务器组名
     * @var string
     */
    protected $_serverGroup = "main.public";
    /**
     * 数据表名称
     * @var string
     */
    protected $_tableName = "zone_data";
}
?>
