<?php
/**
 * 访问模型类(set)
 *
 * @author xianlinli@gmail.com
 * @example $visitModel = new VisitModel($userId);<br />
 *           $visitModel->sAdd($friendUserId);<br />
 *           $friendUserIdArr = $visitModel->sMembers();<br />
 *           $visitModel->delete();
 * @package Alice
 */
class VisitModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'V';
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