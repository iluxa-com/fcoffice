<?php
/**
 * 已赠送模型类(免费礼物)(set)
 *
 * @author xianlinli@gmail.com
 * @example $sendModel = new SendModel($userId);<br />
 *           $sendModel->sAdd($friendUserId);<br />
 *           $friendUserIdArr = $sendModel->sMembers();<br />
 *           $sendModel->delete();
 * @package Alice
 */
class SendModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'S';
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