<?php
/**
 * 任务数据模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $taskDataModel = new TaskDataModel($taskId);<br />
 *           $taskDataModel->exists();<br />
 *           $taskDataModel->hMset($dataArr);<br />
 *           $dataArr = $taskDataModel->hGetAll();
 * @package Alice
 */
class TaskDataModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'main.public';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'TD';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'task_id' => 'int',
        'zone_id' => 'int',
        'place_id' => 'int',
        'npc_id' => 'int',
        'type' => 'int',
        'grade' => 'int',
        'need' => 'string',
        'reward' => 'string',
        'description1' => 'string',
        'description2' => 'string',
        'description3' => 'string',
    );
}
?>