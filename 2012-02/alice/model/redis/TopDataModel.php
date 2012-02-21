<?php
/**
 * 排名数据模型类(string)
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class TopDataModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'main.stat';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'TOD';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
    );
}
?>