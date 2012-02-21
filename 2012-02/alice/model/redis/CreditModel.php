<?php
/**
 * 荣誉模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $creditModel = new CreditModel($userId);<br />
 *           $creditModel->hIncrBy($creditId, $num);<br />
 *           $dataArr = $creditModel->hGetAll();
 * @package Alice
 */
class CreditModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'CR';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'credit_id1' => 'num',
        'credit_id2' => 'num',
    );
}
?>