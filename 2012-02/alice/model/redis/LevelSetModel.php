<?php
/**
 * 关卡集合模型类(set)
 *
 * @author xianlinli@gmail.com
 * @example $levelSetModel = new LevelSetModel($areaId, $skillLevel, $difficulty);<br />
 *           $levelSetModel->sAdd($levelId);<br />
 *           $dataArr = $levelSetModel->sMembers();
 * @package Alice
 */
class LevelSetModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'main.public';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'LS';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'level_id1',
        'level_id2',
    );
}
?>