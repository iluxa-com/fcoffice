<?php
/**
 * 任务模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $taskModel = new TaskModel($userId);<br />
 *           $taskModel->hSet('active', $hashKey);
 * @package Alice
 */
class TaskModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'T';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'info' => '{"task_id":1,"friends":[1,2,3,4,5]}',
        'active' => 'friend_user_id:task_id',
        'temp' => '{}',
    );
    /**
     * 未完成任务
     * @var string
     */
    const TASK_UNFINISHED = '0';
    /**
     * 已完成任务
     * @var string
     */
    const TASK_FINISHED = '1';
    /**
     * 好友任务列表
     */
    const FRIEND_TASK_LIST = 'list';
    /**
     * 最大邀请次数限制
     * @var int
     */
    const MAX_INVITE_TIMES = 5;
    /**
     * 好友任务需要收集的数量
     * @var int
     */
    const NEED_COLLECT_NUM = 1;
}
?>