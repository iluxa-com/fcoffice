<?php
/**
 * 动作模型类(string)
 *
 * @author xianlinli@gmail.com
 * @example $actionModel = new ActionModel($subKey);<br />
 *           $actionModel->append($dataStr);<br />
 *           $dataStr = $actionModel->get();
 * @package Alice
 */
class ActionModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'main.action';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'A';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        '2011-11-15-12' => 'dataStr',
    );
}
?>