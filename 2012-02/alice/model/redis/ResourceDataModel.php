<?php
/**
 * 资源数据模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $resourceDataModel = new ResourceDataModel();<br />
 *           $resourceDataModel->hSet($hashKey, $dataStr);<br />
 *           $dataStr = $resourceDataModel->hGet($hashKey);<br />
 *           $dataArr = $resourceDataModel->hGetAll();
 * @package Alice
 */
class ResourceDataModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'main.public';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'resource_data';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'key' => 'data',
    );
}
?>