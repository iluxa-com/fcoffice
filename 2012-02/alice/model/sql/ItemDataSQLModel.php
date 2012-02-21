<?php
/**
 * 道具数据SQL模型类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class ItemDataSQLModel extends SQLModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'main.public';
    /**
     * 表名
     * @var string
     */
    protected $_tableName = 'item_data';
}
?>