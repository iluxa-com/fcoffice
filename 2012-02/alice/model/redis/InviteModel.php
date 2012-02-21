<?php
/**
 * 好友帮助任务邀请模型类(set)
 *
 * @author xianlinli@gmail.com
 * @example $inviteModel = new InviteModel($userId);<br />
 *           $inviteModel->sAdd($friendUserId);<br />
 *           $friendUserIdArr = $inviteModel->sMembers();<br />
 *           $inviteModel->delete();
 * @package Alice
 */
class InviteModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'I';
    /**
     * 结构数组
     */
    protected $_structArr = array(
        'friend_user_id1',
        'friend_user_id2',
    );
}
?>