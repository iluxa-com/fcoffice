<?php
/**
 * 关系模型类(string)
 *
 * @author xianlinli@gmail.com
 * @example $relationModel = new RelationModel($snsUid);<br />
 *           $relationModel->set($userId);<br />
 *           $relationModel->get();
 * @package Alice
 */
class RelationModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'main.relation';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'RE';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'sns_uid' => 'user_id',
    );

    /**
     * 生成下一个用户ID
     * @return int
     */
    public function genNextUserId() {
        $key = 'counter:user_id';
        if (!$this->RH()->exists($key)) { // 设置初始值
            $minUserId = $this->getMinUserId();
            if ($this->RH()->setnx($key, $this->getMinUserId()) === true) {
                return $minUserId;
            }
        }
        return $this->RH()->incr($key);
    }

    /**
     * 获取存储前缀
     * @return string
     */
    public function getStoreKeyPrefix() {
        return $this->_storeKeyPrefix;
    }

    /**
     * 获取最小用户ID
     * @return int
     */
    public function getMinUserId() {
        return 10001;
    }

    /**
     * 获取最大用户ID
     * @return int
     */
    public function getMaxUserId() {
        $key = 'counter:user_id';
        return $this->RH()->get($key);
    }
}
?>