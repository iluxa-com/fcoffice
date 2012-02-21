<?php
/**
 * Circle缓存模型类(string)
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class CircleCacheModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'CC';
    /**
     * 是否用作缓存
     * @var bool 
     */
    protected $_useForCache = true;
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
    );

    /**
     * 获取服务器组
     * @return string
     */
    public function getServerGroup() {
        return $this->_serverGroup;
    }
}
?>