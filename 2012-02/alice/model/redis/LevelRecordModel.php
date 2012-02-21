<?php
/**
 * 关卡记录模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $levelRecordModel = new LevelRecordModel($userId);<br />
 *           $levelRecordModel->hIncrBy($areaId, 1);<br />
 *           $num = $levelRecordModel->hGet($areaId);
 * @package Alice
 */
class LevelRecordModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'LR';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'area_id1' => 'num',
        'area_id2' => 'num',
    );
}
?>