<?php
/**
 * 索求模型类(set)
 *
 * @author xianlinli@gmail.com
 * @example $requestModel = new RequestModel($userId);<br />
 *           $requestModel->sAdd($friendUserId);<br />
 *           $friendUserIdArr = $requestModel->sMembers();<br />
 *           $requestModel->delete();
 * @package Alice
 */
class RequestModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'R';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'friend_user_id1',
        'friend_user_id2',
    );
}
?>