<?php
/**
 * 宝箱邀请模型类(set)
 *
 * @author xianlinli@gamil.com
 * @example $chestInviteModel = new ChestInviteModel($userId, $flag);<br />
 *           $chestInviteModel->sAdd($friendUserId);<br />
 *           $friendUserIdArr = $blessModel->sMembers();<br />
 *           $chestInviteModel->delete();
 * @package Alice
 */
class ChestInviteModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'CI';
    /**
     * 结构数组
     */
    protected $_structArr = array(
        'friend_user_id1',
        'friend_user_id2',
    );
}
?>