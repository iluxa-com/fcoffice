<?php
/**
 * 套装数据模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $suitDataModel = new SuitDataModel();<br />
 *           $suitDataModel->hSet($hashKey, $dataStr);<br />
 *           $dataStr = $suitDataModel->hGet($hashKey);<br />
 *           $dataArr = $suitDataModel->hGetAll();
 * @package Alice
 */
class SuitDataModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'main.public';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'suit_data';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'suit_id' => 'data',
    );

    /**
     * 生成下一个套装ID
     * @return int
     */
    public function genNextSuitId() {
        $key = 'counter:suit_id';
        return $this->RH()->incr($key);
    }
}
?>