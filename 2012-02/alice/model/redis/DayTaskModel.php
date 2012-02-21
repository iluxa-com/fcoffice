<?php
/**
 * 每日任务模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $dayTaskModel = new DayTaskModel($userId);<br />
 *           $dayTaskModel->hIncrBy($itemId, $num);<br />
 *           $num = $dayTaskModel->hGet($itemId);<br />
 *           $dayTaskModel->hDel($itemId);<br />
 *           $dataArr = $dayTaskModel->hGetAll();
 * @package Alice
 */
class DayTaskModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'DT';
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