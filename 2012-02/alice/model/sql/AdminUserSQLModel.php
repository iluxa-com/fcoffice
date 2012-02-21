<?php
/*
 * 后台用户SQL模型类
 *
 * @seaonxin@yahoo.cn
 * @package Alice
 */
class AdminUserSQLModel extends SQLModel {
    /**
     *  服务器组名
     * @var string
     */
    protected $_serverGroup = "main.public";
    /**
     * 数据表名称
     * @var string
     */
    protected $_tableName = "admin_user";
}
?>
