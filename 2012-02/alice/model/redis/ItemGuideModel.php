<?php
/**
 * 道具指导模型类(set)
 *
 * @author xianlinli@gmail.com
 * @example $itemGuideModel = new ItemGuideModel($userId);<br />
 *           $itemGuideModel->sAdd($itemId);<br />
 *           $itemIdArr = $itemGuideModel->sMembers();
 * @package Alice
 */
class ItemGuideModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'IG';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'item_id1',
        'item_id2',
    );
}
?>