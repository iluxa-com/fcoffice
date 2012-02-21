<?php
/**
 * 服装数据模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $dressDataModel = new DressDataModel();<br />
 *           $dressDataModel->hSet($hashKey, $dataStr);<br />
 *           $dataStr = $dressDataModel->hGet($hashKey);<br />
 *           $dataArr = $dressDataModel->hGetAll();
 * @package Alice
 */
class DressDataModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'main.public';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'dress_data';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'key' => 'data',
    );

    /**
     * 生成下一个服装ID
     * @return int
     */
    public function genNextDressId() {
        $key = 'counter:dress_id';
        return $this->RH()->incr($key);
    }
}
?>