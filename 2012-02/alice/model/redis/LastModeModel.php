<?php
/**
 * 最后一次闯关模式记录模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $lastModeModel = new LastModeModel($userId);<br />
 *           $lastModeModel->hSet($areaId, $mode);<br />
 *           $mode = $lastModeModel->hGet($areaId);
 * @package Alice
 */
class LastModeModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'LM';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'area_id1' => '0',
        'area_id2' => '1',
    );
}
?>