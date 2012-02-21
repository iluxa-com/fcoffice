<?php
/**
 * 统计数据模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $statDataModel = new StatDataModel($subKey, $date);<br />
 *           $statDataModel->hIncrBy($hashKey, $num);<br />
 *           $num = $statDataModel->hGet($hashKey);<br />
 *           $dataArr = $statDataModel->hGetAll();
 * @package Alice
 */
class StatDataModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'main.stat';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'SD';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
    );
}
?>