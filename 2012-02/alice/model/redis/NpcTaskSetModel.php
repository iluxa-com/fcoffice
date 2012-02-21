<?php
/**
 * NPC任务集合模型类(set)
 *
 * @author xianlinli@gmail.com
 * @example $npcTaskSetModel = new NpcTaskSetModel($areaId, $grade);<br />
 *           $npcTaskSetModel->sAdd($taskId);<br />
 *           $taskIdArr = $npcTaskSetModel->sMembers();
 * @package Alice
 */
class NpcTaskSetModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'main.public';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'NTS';
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