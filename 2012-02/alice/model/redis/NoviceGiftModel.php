<?php
/**
 * 新手礼包模型类(string)
 *
 * @author xianlinli@gmail.com
 * @example $noviceGiftModel = new NoviceGiftModel($userId);<br />
 *           $noviceGiftModel->setBit($offset, $value);<br />
 *           $value = $noviceGiftModel->getBit($offset);<br />
 *           $noviceGiftModel->get();
 * @package Alice
 */
class NoviceGiftModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'NG';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        '10000000',
    );
}
?>