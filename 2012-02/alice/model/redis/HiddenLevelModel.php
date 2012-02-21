<?php
/**
 * 隐藏关卡模型类(set & hash)
 *
 * @author xianlinli@gmail.com
 * @example $hiddenLevelModel = new HiddenLevelModel($userId, $type);<br />
 *           $hiddenLevelModel->sAdd($key);<br />
 *           $keyArr = $hiddenLevelModel->sMembers();<br />
 *           $hiddenLevelModel->delete();
 * @package Alice
 */
class HiddenLevelModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'HL';
    /**
     * 结构数组
     */
    protected $_structArr = array(
        'area_id-node_id1',
        'area_id-node_id2',
    );
    /**
     * 已解锁
     */
    const TYPE_UNLOCKED = 0;
    /**
     * 当日已完成
     */
    const TYPE_FINISHED = 1;
}
?>