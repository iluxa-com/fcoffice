<?php
/**
 * 祝福模型类(set)
 *
 * @author wangr@gmail.com
 * @example $blessModel = new BlessModel($userId);<br />
 *           $blessModel->sAdd($friendUserId);<br />
 *           $friendUserIdArr = $blessModel->sMembers();<br />
 *           $blessModel->delete();
 * @package Alice
 */
class BlessModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'BL';
    /**
     * 结构数组
     */
    protected $_structArr = array(
        'friend_user_id1',
        'friend_user_id2',
    );
}
?>