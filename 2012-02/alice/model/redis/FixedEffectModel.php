<?php
/**
 * 固定效果模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $fixedEffectModel = new FixedEffectModel($userId);<br />
 *           $fixedEffectModel->hIncrBy($effectId, $val);<br />
 *           $val = $fixedEffectModel->hGet($effectId);<br />
 *           $fixedEffectModel->hDel($effectId);<br />
 *           $dataArr = $fixedEffectModel->hGetAll();
 * @package Alice
 */
class FixedEffectModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'FE';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'e1' => 'val',
        'e2' => 'val',
        'e3' => 'val',
        'e4' => 'val',
    );
}
?>