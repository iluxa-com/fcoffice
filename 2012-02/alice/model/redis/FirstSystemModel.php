<?php
/**
 * 第一次系统模型类(string)
 *
 * @author xianlinli@gmail.com
 * @example $firstSystemModel = new FirstSystemModel($userId);<br />
 *           $firstSystemModel->setBit($offset, $value);<br />
 *           $value = $firstSystemModel->getBit($offset);<br />
 *           $firstSystemModel->get();
 * @package Alice
 */
class FirstSystemModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'FS';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        '10000000',
    );
}
?>