<?php
/**
 * 好友模型类(string)
 *
 * @author xianlinli@gmail.com
 * @example $friendModel = new FriendModel($userId);<br />
 *           $friendModel->set($jsonStr);<br />
 *           $jsonStr = $friendModel->get();
 * @package Alice
 */
class FriendModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.friend';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'F';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'json_str',
    );
}
?>