<?php
/**
 * 新手任务模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $initTaskModel = new InitTaskModel($userId);<br />
 *           $initTaskModel->hIncrBy($itemId, $num);<br />
 *           $num = $initTaskModel->hGet($itemId);<br />
 *           $initTaskModel->hDel($itemId);<br />
 *           $dataArr = $initTaskModel->hGetAll();
 * @package Alice
 */
class InitTaskModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'IT';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'task_id1' => 'num',
        'task_id2' => 'num',
    );
}
?>