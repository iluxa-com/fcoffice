<?php
/**
 * 关卡数据模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $levelDataModel = new LevelDataModel();<br />
 *           $levelDataModel->hSet($levelId, $jsonStr);<br />
 *           $jsonStr = $levelDataModel->hGet($levelId);<br />
 *           $dataArr = $levelDataModel->hGetAll();
 * @package Alice
 */
class LevelDataModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'main.public';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'level_data';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'level_id' => 'json_str',
    );

    /**
     * 生成下一个关卡ID
     * @return int
     */
    public function genNextLevelId() {
        $key = 'counter:level_id';
        return $this->RH()->incr($key);
    }
}
?>