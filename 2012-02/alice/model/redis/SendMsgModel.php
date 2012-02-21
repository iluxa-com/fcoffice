<?php
/**
 * 留言模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $sendMsgModel = new SendMsgModel($userId);<br />
 *           $sendMsgModel->hIncrBy($friendUserId, $num);<br />
 *           $num = $sendMsgModel->hGet($friendUserId);<br />
 *           $dataArr = $sendMsgModel->hGetAll();
 * @package Alice
 */
class SendMsgModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'SM';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'friend_user_id1' => 'num',
        'friend_user_id2' => 'num',
    );
}
?>