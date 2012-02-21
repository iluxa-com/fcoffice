<?php
/**
 * 互斥锁模型类(string)
 *
 * @author xianlinli@gmail.com
 * @example $mutexModel = new MutexModel($userId, $service, $action);<br />
 *           $mutexModel->setnx($mutexExpire);<br />
 *           $lastMutexExpire = $mutexModel->getSet($newMutexExpire);<br />
 *           $mutexModel->delete();
 * @package Alice
 */
class MutexModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'M';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'mutex_key' => 'mutex_expire',
    );
    /**
     * 互斥锁过期时间(s)
     * @var float
     */
    private $_mutexExpire = 0;
    /**
     * 货架
     */
    const SHELF_ITEM = 0;
    /**
     * 发布任务
     */
    const SLOT_TASK = 1;

    /**
     * 添加互斥锁
     * @param float $mutexTime 互斥时间(s)
     * @return bool true表示添加成功,false表示添加失败
     */
    public function addMutex($mutexTime) {
        // 互斥锁过期时间
        $mutexExpire = microtime(true) + $mutexTime;
        if ($this->setnx($mutexExpire) === false) { // 设置失败
            // 上次互斥过期时间
            $lastMutexExpire = $this->get();
            if ($lastMutexExpire !== false && microtime(true) <= $lastMutexExpire) { // 另一个线(进)程已经优先进入互斥操作
                return false;
            }
            // 新互斥锁过期时间
            $mutexExpire = microtime(true) + $mutexTime;
            $lastMutexExpire = $this->getSet($mutexExpire);
            if ($lastMutexExpire !== false && microtime(true) <= $lastMutexExpire) { // 另一个线(进)程已经优先进入了互斥操作
                return false;
            }
        }
        $this->_mutexExpire = $mutexExpire;
        return true;
    }

    /**
     * 移除互斥锁
     */
    public function removeMutex() {
        if ($this->_mutexExpire > 0) {
            // 这里转化成字符串来比较,因为浮点数比较结果可能会不正确
            $this->get() === strval($this->_mutexExpire) && $this->delete();
            $this->_mutexExpire = 0;
        }
    }
}
?>