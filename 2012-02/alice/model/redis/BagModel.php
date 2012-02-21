<?php
/**
 * 背包模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $bagModel = new BagModel($userId);<br />
 *           $bagModel->hIncrBy($itemId, $num);<br />
 *           $num = $bagModel->hGet($itemId);<br />
 *           $bagModel->hDel($itemId);<br />
 *           $dataArr = $bagModel->hGetAll();
 * @package Alice
 */
class BagModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'B';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'item_id1' => 'num',
        'item_id2' => 'num',
    );
}
?>