<?php
/**
 * 马车贸易模型类(set)
 *
 * @author falcon_chen@qq.com
 * @example  $HorseTradeModel = new HorseTradeModel($userId);<br />
 *           $HorseTradeModel->sAdd($friendUserId);<br />
 *           $friendUserIdArr = $HorseTradeModel->sMembers();<br />
 *           $HorseTradeModel->delete();
 * @package Alice
 */
class HorseTradeModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'HT';
    /**
     * 结构数组
     */
    protected $_structArr = array(
        'friend_user_id1',
        'friend_user_id2',
    );
}
?>