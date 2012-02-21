<?php
/**
 * 服装模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $dressModel = new DressModel($userId);<br />
 *           $dressModel->hMset($dataArr);<br />
 *           $dressModel->hSet($part, $itemId);<br />
 *           $itemId = $dressModel->hGet($part);<br />
 *           $dataArr = $dressModel->hGetAll();
 * @package Alice
 */
class DressModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'D';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'head' => 'item_id',
        'body_up' => 'item_id',
        'hand' => 'item_id',
        'body_down' => 'item_id',
        'socks' => 'item_id',
        'foot' => 'item_id',
        'other' => 'item_id',
    );
}
?>