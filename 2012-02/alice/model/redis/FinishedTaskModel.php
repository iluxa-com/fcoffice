<?php
/**
 * 已完成任务模型类(set)
 *
 * @author xianlinli@gmail.com
 * @example $finishedTaskModel = new FinishedTaskModel($userId);<br />
 *           $finishedTaskModel->sAdd($taskId);<br />
 *           $taskIdArr = $finishedTaskModel->sMembers();
 * @package Alice
 */
class FinishedTaskModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'FT';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'task_id1',
        'task_id2',
    );
}
?>