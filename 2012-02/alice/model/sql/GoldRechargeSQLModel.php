<?php
/**
 * 金币充值SQL模型类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class GoldRechargeSQLModel extends SQLModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.log';
    /**
     * 表名
     * @var string
     */
    protected $_tableName = 'gold_recharge';

    /**
     * 获取服务器组
     * @return string
     */
    public function getServerGroup() {
        return $this->_serverGroup;
    }
}
?>