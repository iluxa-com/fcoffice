<?php
/**
 * 宠物模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $petModel = new PetModel($userId, $itemId);<br />
 *           $petModel->hIncrBy('exp', $exp);<br />
 *           $petModel->hIncrBy('happy', $happy);<br />
 *           $petModel->delete;<br />
 *           $dataArr = $petModel->hGetAll();
 * @package Alice
 */
class PetModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'P';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'item_id' => 'int',
        'exp' => 'int', // 经验值
        'happy' => 'int', // 开心值
        'last_feed' => 'int', // 上次喂食时间
    );
}
?>