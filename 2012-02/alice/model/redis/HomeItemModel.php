<?php
/**
 * 家装饰道具模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $homeItemModel = new HomeItemModel($userId, $uid);<br />
 *           $homeItemModel->hMset($dataArr);<br />
 *           $dataArr = $homeItemModel->hGetAll();
 * @package Alice
 */
class HomeItemModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'HI';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'uid' => 'int',
        'item_id' => 'int',
        'x' => 'int',
        'y' => 'int',
    );
}
?>