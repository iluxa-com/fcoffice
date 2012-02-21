<?php
/**
 * 关卡闯关最高分数模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $topPointModel = new TopPointModel($userId, $areaId, $mode);<br />
 *           $topPointModel->hSet($nodeId, $point);<br />
 *           $point = $topPointModel->hGet($nodeId);
 * @package Alice
 */
class TopPointModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'TP';
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