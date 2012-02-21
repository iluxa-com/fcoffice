<?php
/**
 * 标志模型类(string)
 *
 * @author xianlinli@gmail.com
 * @example $flagModel = new FlagModel($userId, $subKey);<br />
 *           $flagModel->setBit($offset, $value);<br />
 *           $value = $flagModel->getBit($offset);<br />
 *           $flagModel->get();
 * @package Alice
 */
class FlagModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'FL';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        '10000000',
    );
    /**
     * 图鉴
     */
    const TYPE_COLLECTION = 0;
    /**
     * 奖杯
     */
    const TYPE_CUP = 1;
    /**
     * Feed
     */
    const TYPE_FEED = 2;
}
?>