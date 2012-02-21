<?php
/**
 * 好友任务模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $friendTaskModel = new FriendTaskModel($userId);<br />
 *           $friendTaskModel->hSet($hashKey, $jsonStr);<br />
 *           $jsonStr = $friendTaskModel->hGet($hashKey);
 * @package Alice
 */
class FriendTaskModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'FR';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'friend_user_id:task_id' => '{"user_id":9527,"task_id":1,"num":5,"time":124324323}',
    );
}
?>