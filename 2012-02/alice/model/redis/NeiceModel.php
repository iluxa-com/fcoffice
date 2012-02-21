<?php
/**
 * 内测奖励模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $neiceModel = new NeiceModel($snsUid);<br />
 *           $neiceModel->hMset($dataArr);<br />
 *           $dataArr = $neiceModel->hGetAll();
 * @package Alice
 */
class NeiceModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.neice';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'neice';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'old' => 'grade', // 旧版中的等级
        'new' => 'grade', // 新版中的等级
        'flag' => 'int', // 是否领取标志
    );
}
?>