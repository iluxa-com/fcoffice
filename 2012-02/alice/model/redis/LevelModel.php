<?php
/**
 * 关卡模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $levelModel = new LevelModel($userId);<br />
 *           $levelModel->hMset($dataArr);<br />
 *           $levelModel->hGetAll();
 * @package Alice
 */
class LevelModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'L';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'zone_id' => 'int',
        'place_id' => 'int',
        'current' => 'int', // 当前正在玩的关卡ID
        'finished' => 'string', // 已经完成的关卡ID(逗号分隔)
        'difficulty' => 'int', // 难度
    );
}
?>