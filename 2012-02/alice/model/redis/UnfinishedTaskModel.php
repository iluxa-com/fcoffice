<?php
/**
 * 未完成任务模型类(set)
 *
 * @author xianlinli@gmail.com
 * @example $unfinishedTaskModel = new UnfinishedTaskModel($userId);<br />
 *           $unfinishedTaskModel->sAdd($taskId);<br />
 *           $taskIdArr = $unfinishedTaskModel->sMembers();
 * @package Alice
 */
class UnfinishedTaskModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'UT';
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