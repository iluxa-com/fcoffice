<?php
/**
 * 动态日志SQL模型类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class NewsLogSQLModel extends SQLModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.log';
    /**
     * 表名
     * @var string
     */
    protected $_tableName = 'news_log';
}
?>