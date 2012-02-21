<?php
/**
 * 任务槽模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $taskSlotModel = new TaskSlotModel($userId);<br />
 *           $taskSlotModel->hSet($hashKey, $jsonStr);<br />
 *           $jsonStr = $taskSlotModel->hGet($hashKey);
 * @package Alice
 */
class TaskSlotModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'TS';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'slot_id1' => '{"task_id":1,"time":124324323,"user_id":0}',
        'slot_id2' => '{"task_id":2,"time":124324323,"user_id":9527}',
    );
}
?>