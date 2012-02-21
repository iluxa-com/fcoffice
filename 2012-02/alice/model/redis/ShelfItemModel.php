<?php
/**
 * 货架道具模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $shelfItemModel = new ShelfItemModel($userId, $shelfId);<br />
 *           $shelfItemModel->hMset($dataArr);<br />
 *           $dataArr = $shelfItemModel->hGetAll();
 * @package Alice
 */
class ShelfItemModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'SI';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'shelf_id1' => '{"item_id":1001,"num":10,"price":100}',
        'shelf_id2' => '{"item_id":1002,"num":20,"price":200}',
    );
}
?>