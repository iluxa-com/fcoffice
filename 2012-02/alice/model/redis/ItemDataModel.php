<?php
/**
 * 道具数据模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $itemDataModel = new ItemDataModel($itemId);<br />
 *           $itemDataModel->exists();<br />
 *           $itemDataModel->hMset($dataArr);<br />
 *           $dataArr = $itemDataModel->hGetAll();
 * @package Alice
 */
class ItemDataModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'main.public';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'ID';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'item_id' => 'int',
        'item_name' => 'string',
        'extra_info' => 'string',
        'description' => 'string',
    );
}
?>