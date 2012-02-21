<?php
/**
 * 收藏模型类(set)
 *
 * @author xianlinli@gmail.com
 * @example $collectionModel = new CollectionModel($userId);<br />
 *           $collectionModel->sAdd($itemId);<br />
 *           $itemIdArr = $collectionModel->sMembers();<br />
 *           $collectionModel->delete();
 * @package Alice
 */
class CollectionModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'C';
    /**
     * 结构数组
     */
    protected $_structArr = array(
        'item_id1',
        'item_id2',
    );
}
?>