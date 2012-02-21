<?php
/**
 * 交易服务类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class TradeService extends Service {
    /**
     * 互斥锁互斥时间(秒)
     * @var float
     */
    private $_lockTime = 0.8;
    /**
     * 互斥锁模型实例
     * @var MutexModel
     */
    private $_lockModel;

    /**
     * 解锁货架
     * @param int $shelfId 货架ID
     * @param int $type 类型(0=达到等级手动解锁,1=使用FH币解锁)
     */
    public function unlockShelfItem($shelfId = NULL, $type = NULL) {
        if (!in_array($type, array(0, 1))) {
            $this->_data['msg'] = 'invalid type';
        }
        // 货架配置
        $conifArr = array(
            1 => array('grade' => 10, 'gold' => 10),
            2 => array('grade' => 15, 'gold' => 25),
            3 => array('grade' => 20, 'gold' => 40),
            4 => array('grade' => 25, 'gold' => 55),
            5 => array('grade' => 30, 'gold' => 70),
            6 => array('grade' => 35, 'gold' => 85),
            7 => array('grade' => 40, 'gold' => 100),
            8 => array('grade' => 45, 'gold' => 115),
        );
        if (!isset($conifArr[$shelfId])) { // 不存在
            $this->_data['msg'] = 'invalid shelf id';
            return;
        }
        $shelfItemModel = new ShelfItemModel();
        if ($shelfItemModel->hExists($shelfId)) { // 已经解锁
            $this->_data['msg'] = 'already unlocked';
            return;
        }
        if ($type == 0) {
            $curGrade = Common::getGradeByExp($this->_userModel->hGet('exp'));
            if ($curGrade < $conifArr[$shelfId]['grade']) { // 等级不够
                $this->_data['msg'] = 'grade too lower';
                return;
            }
        } else {
            $needNum = $conifArr[$shelfId]['gold'];
            $curNum = Bag::getItemNum(Common::ITEM_GOLD);
            if ($curNum < $needNum) { // FH币不够
                $this->_data['msg'] = 'not enough gold';
                return;
            }
            if (Bag::decrItem(Common::ITEM_GOLD, $needNum) === false) { // 扣除失败
                $this->_data['msg'] = 'decr item fail';
                return;
            }
        }
        if ($shelfItemModel->hSetNx($shelfId, '{}') === false) {
            if ($type == 1) {
                Bag::incrItem(Common::ITEM_GOLD, $needNum); // 失败回滚
            }
            $this->_data['msg'] = 'unknown error';
            return;
        }
        $this->_ret = 0;
    }

    /**
     * 上架道具
     * @param int $shelfId 货架ID
     * @param int $itemId 道具ID
     * @param int $num 数量
     * @param int $price 价格
     */
    public function shelveItem($shelfId = NULL, $itemId = NULL, $num = NULL, $price = NULL) {
        if (!is_numeric($shelfId)) {
            $this->_data['msg'] = 'invalid sid';
            return;
        }
        if (!is_numeric($itemId)) {
            $this->_data['msg'] = 'invalid item id';
            return;
        }
        if (!is_numeric($num) || $num < 1 || intval($num) != $num) {
            $this->_data['msg'] = 'invalid num';
            return;
        }
        if (!is_numeric($price) || $price <= 0) {
            $this->_data['msg'] = 'invalid price';
            return;
        }
        $shelfItemModel = new ShelfItemModel();
        $jsonStr = $shelfItemModel->hGet($shelfId, false);
        if ($jsonStr === false) { // 货架没解锁
            $this->_data['msg'] = 'shelf unlock';
            return;
        }
        $jsonArr = json_decode($jsonStr, true);
        if ($jsonArr === NULL) { // 解码失败
            $this->_data['msg'] = 'json decode fail';
            return;
        }
        if (!empty($jsonArr)) { // 货架不为空
            $this->_data['msg'] = 'shelf not empty';
            return;
        }
        if (Bag::decrItem($itemId, $num) === false) { // 背包扣除失败
            $this->_data['msg'] = 'decr item fail';
            return;
        }
        // 数据
        $dataArr = array(
            'item_id' => $itemId,
            'num' => $num,
            'price' => $price,
            'time' => CURRENT_TIME,
        );
        if ($shelfItemModel->hSet($shelfId, json_encode($dataArr)) === false) { // 数据设置失败
            Bag::incrItem($itemId, $num); // 失败回滚
            $this->_data['msg'] = 'hMset fail';
            return;
        }
        $this->_ret = 0;
    }

    /**
     * 下架道具
     * @param int $shelfId 货架ID
     */
    public function unshelveItem($shelfId = NULL) {
        if (!is_numeric($shelfId)) {
            $this->_data['msg'] = 'invalid shelf id';
            return;
        }
        if ($this->__addLock($this->_userId, $shelfId) === false) { // 加锁失败
            $this->_data['msg'] = 'add mutex fail';
            return;
        }
        $shelfItemModel = new ShelfItemModel();
        $jsonStr = $shelfItemModel->hGet($shelfId, false);
        if ($jsonStr === false) { // 货架没解锁
            $this->__removeLock();
            $this->_data['msg'] = 'shelf unlock';
            return;
        }
        $jsonArr = json_decode($jsonStr, true);
        if ($jsonArr === NULL) { // 解码失败
            $this->__removeLock();
            $this->_data['msg'] = 'json decode fail';
            return;
        }
        if (empty($jsonArr)) { // 道具不存在
            $this->__removeLock();
            $this->_data['msg'] = 'item not exists';
            return;
        }
        if ($jsonArr['time'] + 3600 > CURRENT_TIME) { // 至少上架后一个小时才能下架
            $this->__removeLock();
            $this->_data['msg'] = 'item cannot unshelve after shelve without one hour';
            return;
        }
        if ($shelfItemModel->hSet($shelfId, '{}') === false) {
            $this->__removeLock();
            $this->_data['msg'] = 'hSet fail';
            return;
        }
        $itemId = $jsonArr['item_id'];
        $num = $jsonArr['num'];
        if (Bag::incrItem($itemId, $num) === false) { // 增加道具失败
            $shelfItemModel->hSet($shelfId, $jsonStr); // 失败回滚
            $this->__removeLock();
            $this->_data['msg'] = 'incr item fail';
            return;
        }
        $this->__removeLock();
        $this->_ret = 0;
    }

    /**
     * 购买道具
     * @param int $friendUserId 好友用户ID
     * @param int $shelfId 货架ID
     * @param int $buyNum 购买数量
     */
    public function buyItem($friendUserId = NULL, $shelfId = NULL, $buyNum = NULL) {
        if (!is_numeric($friendUserId)) {
            $this->_data['msg'] = 'invalid friend user id';
            return;
        }
        if (!is_numeric($shelfId)) {
            $this->_data['msg'] = 'invalid sid';
            return;
        }
        if (!is_numeric($buyNum) || $buyNum < 1 || intval($buyNum) != $buyNum) {
            $this->_data['msg'] = 'invalid buy num';
        }
        if ($friendUserId == $this->_userId) { // 不能为自己
            $this->_data['msg'] = 'cannot buy item from self';
            return;
        }
        if ($this->__addLock($friendUserId, $shelfId) === false) { // 加锁失败
            $this->_data['msg'] = 'add mutex fail';
            return;
        }
        $friendShelfItemModel = new ShelfItemModel($friendUserId);
        $jsonStr = $friendShelfItemModel->hGet($shelfId, false);
        if ($jsonStr === false) { // 货架没解锁
            $this->__removeLock();
            $this->_data['msg'] = 'shelf unlock';
            return;
        }
        $jsonArr = json_decode($jsonStr, true);
        if ($jsonArr === NULL) { // 解码失败
            $this->__removeLock();
            $this->_data['msg'] = 'json decode fail';
            return;
        }
        if (empty($jsonArr)) { // 道具不存在
            $this->__removeLock();
            $this->_data['msg'] = 'item not exists';
            return;
        }
        $itemId = $jsonArr['item_id'];
        $num = $jsonArr['num'];
        $price = $jsonArr['price'];
        if ($buyNum > $num) { // 购买数量大于别人卖的数量
            $this->__removeLock();
            $this->_data['msg'] = 'buy num too bigger';
            return;
        }
        if ($buyNum == $num) { // 全买了
            if ($friendShelfItemModel->hSet($shelfId, '{}') === false) { // 货架清空失败
                $this->__removeLock();
                $this->_data['msg'] = 'hSet fail';
                return;
            }
        } else { // 只买一部分
            $jsonArr['num'] -= $buyNum; // 减去购买的数量
            if ($friendShelfItemModel->hSet($shelfId, json_encode($jsonArr)) === false) { // 更新数据
                $this->__removeLock();
                $this->_data['msg'] = 'hSet fail2';
                return;
            }
        }
        if (Bag::decrItem(Common::ITEM_SILVER, $price * $buyNum) === false) { // 扣自己的金币失败
            $friendShelfItemModel->hSet($shelfId, $jsonStr); // 回滚
            $this->__removeLock();
            $this->_data['msg'] = 'decr item fail';
            return;
        }
        if (Bag::incrItem(Common::ITEM_SILVER, floor($price * $buyNum * 0.9), $friendUserId) === false) { // 往好友背包加金币失败
            $friendShelfItemModel->hSet($shelfId, $jsonStr); // 回滚
            Bag::incrItem(Common::ITEM_SILVER, $price * $buyNum);
            $this->__removeLock();
            $this->_data['msg'] = 'incr item fail';
            return;
        }
        if (Bag::incrItem($itemId, $buyNum) === false) { // 往自己背包加道具失败
            $friendShelfItemModel->hSet($shelfId, $jsonStr); // 回滚数据更新
            Bag::incrItem(Common::ITEM_SILVER, $price * $buyNum); // 回滚扣自己的金币
            Bag::decrItem(Common::ITEM_SILVER, floor($price * $buyNum * 0.9), $friendUserId); // 回滚往好友背包加金币
            $this->__removeLock();
            $this->_data['msg'] = 'incr item fail';
            return;
        }
        $this->__removeLock();
        $this->_ret = 0;
    }

    /**
     * 获取所有货架道具
     * @param int $userId 用户ID
     */
    public function getAllItem($userId = NULL) {
        if (!is_numeric($userId)) {
            $this->_data['msg'] = 'invalid user id';
            return;
        }
        $shelfItemModel = new ShelfItemModel($userId);
        $this->_data['shelf'] = $shelfItemModel->hGetAll();
        $this->_ret = 0;
    }

    /**
     * 加锁
     * @param int $friendUserId 好友用户ID
     * @param int $shelfId 货架ID
     */
    private function __addLock($friendUserId, $shelfId) {
        $this->_lockModel = new MutexModel($friendUserId, MutexModel::SHELF_ITEM, $shelfId);
        return $this->_lockModel->addMutex($this->_lockTime);
    }

    /**
     * 移除锁
     */
    private function __removeLock() {
        $this->_lockModel && $this->_lockModel->removeMutex();
    }
}
?>