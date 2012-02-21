<?php
/**
 * 发布任务集合模型类(set)
 *
 * @author xianlinli@gmail.com
 * @example $slotTaskSetModel = new SlotTaskSetModel($grade);<br />
 *           $slotTaskSetModel->sAdd($taskId);<br />
 *           $taskIdArr = $slotTaskSetModel->sMembers();
 * @package Alice
 */
class SlotTaskSetModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'main.public';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'STS';
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