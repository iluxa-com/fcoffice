<?php
/**
 * 计数器模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $multiKeyModel = new MultiKeyModel($userId, $type);<br />
 *           $multiKeyModel->sAdd($key);<br />
 *           $multiKeyModel->sMembers();
 * @package Alice
 */
class MultiKeyModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'MK';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'key1',
        'key2',
    );
    /**
     * HomeItem模型用
     * @var string
     */
    const HOME_ITEM = 'HI';
    /**
     * Pet模型用
     * @var string
     */
    const PET = 'P';
}
?>