<?php
/**
 * 地图数据模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $mapDataModel = new MapDataModel();<br />
 *           $mapDataModel->hSet($hashKey, $dataStr);<br />
 *           $dataStr = $mapDataModel->hGet($hashKey);<br />
 *           $dataArr = $dressDataModel->hGetAll();
 * @package Alice
 */
class MapDataModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'main.public';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'map_data';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'key' => 'data',
    );
}
?>