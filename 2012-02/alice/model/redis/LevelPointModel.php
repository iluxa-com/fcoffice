<?php
/**
 * 挑战模式闯关关卡奖杯分数模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $levelPointModel = new LevelPointModel($userId, $areaId);<br />
 *           $levelPointModel->hIncrBy($nodeId, $point);<br />
 *           $point = $levelPointModel->hGet($nodeId);
 * @package Alice
 */
class LevelPointModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'LP';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'node_id1' => 'point',
        'node_id2' => 'point',
    );
}
?>