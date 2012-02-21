<?php
/**
 * 道具服务类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class ItemService extends Service {
    /**
     * 购买道具
     * @param int $itemId 道具ID
     * @param int $num 购买数量
     * @param string $type 类型(silver/gold)
     * @param int $needWear 是否替换现有的(仅用于装饰道具,0=不替换,1=替换)
     */
    public function buyItem($itemId = NULL, $num = NULL, $type = NULL, $needWear = 0) {
        if (!is_numeric($itemId)) {
            $this->_data['msg'] = 'invalid item id';
            return;
        }
        if (!is_numeric($num) || $num < 1 || intval($num) != $num) {
            $this->_data['msg'] = 'invalid num';
            return;
        }
        if (!in_array($type, array('silver', 'gold'))) {
            $this->_data['msg'] = 'invalid type';
            return;
        }
        $needWear = ($needWear == 0) ? false : true;
        $curNum = Bag::getItemNum($itemId);
        if ($curNum !== false && ($curNum + $num) > 99) { // 道具数量限制
            $this->_data['msg'] = 'item num reach max';
            $this->_ret = UserException::ERROR_ITEM_REACH_MAX;
            return;
        }
        $itemDataModel = new ItemDataModel($itemId);
        $itemDataArr = $itemDataModel->hGetAll();
        if (empty($itemDataArr)) { // 没取到
            $this->_data['msg'] = 'cannot get item data';
            return;
        }
        $curGrade = Common::getGradeByExp($this->_userModel->hGet('exp'));
        if ($itemDataArr['grade'] > $curGrade) { // 等级不够
            $this->_data['msg'] = 'grade too lower';
            return;
        }
        if ($itemDataArr['buyable'] != '1') { // 此商品不能购买,直接返回
            $this->_data['msg'] = 'item not exists or item not for sale';
            return;
        }
        if ($itemDataArr['extra_info'] !== '') {
            $extraInfoStr = $itemDataArr['extra_info'];
            $extraInfoArr = json_decode($extraInfoStr, true);
            if ($extraInfoArr === NULL) { // decode error
                $this->_data['msg'] = 'extra info decode error';
                return;
            }
            $itemDataArr['extra_info'] = $extraInfoArr;
        }
        if ($type === 'silver') {
            $this->__buySilverItem($itemDataArr, $itemId, $num, $needWear);
            $actionType = User::ACTION_TYPE_BUY_SILVER_ITEM;
            $val3 = $this->_userModel->hGet('silver');
        } else {
            $this->__buyGoldItem($itemDataArr, $itemId, $num, $needWear);
            $actionType = User::ACTION_TYPE_BUY_GOLD_ITEM;
            $val3 = $this->_userModel->hGet('gold');
        }
        if ($this->_ret == 0) {
            // 写动作日志
            $dataArr = array(
                'val1' => $itemId, // 道具ID
                'val2' => $num, // 数量
                'val3' => $val3, // 剩余钱数量
                'val4' => '', // 暂时没用
            );
            User::actionLog($actionType, $dataArr); // 写动作日志(购买道具)
        }
    }

    /**
     * 使用道具
     * @param array $itemArr 道具数组([{"item_id":9527,"num":1},{"item_id":9528,"num":1}])
     */
    public function useItem($itemArr = NULL) {
        if (!is_array($itemArr) || empty($itemArr) || count($itemArr) != 1) {
            $this->_data['msg'] = 'bad request';
            return;
        }
        // 取第一个元素
        $item = $itemArr[0];
        if (!isset($item['item_id']) || !is_numeric($item['item_id'])) {
            $this->_data['msg'] = 'no item id or invalid item id';
            return;
        }
        $itemId = $item['item_id'];
        // 数量(默认为1)
        $num = isset($item['num']) ? $item['num'] : 1;
        /*
         * 道具ID固定为4位
         * 1开头 服装道具(category表部位)
         * 2开头 摆设道具
         * 3开头 关卡道具
         * 4开头 食物道具
         * 5开头 收藏道具
         * 6开头 隐藏关解锁道具
         * 7开头 任务道具
         * 8开头 套装道具
         * 9开头 宠物道具
         */
        if ($itemId >= 1000 && $itemId <= 1999) { // 服装道具
            if ($num != 1) { // 数量限制为1
                $this->_data['msg'] = 'invalid num';
                return;
            }
            if (!($itemId >= 1000 && $itemId <= 1013) && !($itemId >= 1170 && $itemId <= 1181)) { // 非默认的服饰,必须在背包里有
                $curNum = Bag::getItemNum($itemId);
                if ($curNum === false || $curNum < $num) { // 数量不足
                    $this->_data['msg'] = 'not enough item';
                    return;
                }
            }
            if ($this->__wearSingleItem($itemId) !== 0) {
                $this->_data['msg'] = 'wear error';
                return;
            }
        } else if ($itemId >= 2000 && $itemId <= 2999) { // 摆设道具
            // 不在这里处理
            $this->_data['msg'] = 'cannot use here';
            return;
        } else if ($itemId >= 3000 && $itemId <= 3999) { // 关卡道具
            // 不在这里处理
            $this->_data['msg'] = 'cannot use here';
            return;
        } else if ($itemId >= 5001 && $itemId <= 5999) { // 收藏道具(登录到图鉴系统)
            // 不在这里处理
            $this->_data['msg'] = 'cannot use here';
            return;
        } else if ($itemId >= 6001 && $itemId <= 6999) { // 隐藏关卡解锁道具
            if ($num != 1) { // 数量限制为1
                $this->_data['msg'] = 'bad request';
                return;
            }
            if (Bag::decrItem($itemId, $num) === false) {
                $this->_data['msg'] = 'decr item fail';
                return;
            }
            $hiddenLevelModel = new HiddenLevelModel(NULL, HiddenLevelModel::TYPE_UNLOCKED);
            if ($hiddenLevelModel->hSetNx($itemId, 0) === false) {
                $this->_data['msg'] = 'already unlocked or other error';
                return;
            }
        } else if ($itemId >= 8000 && $itemId <= 8999) { // 套装道具
            // 不在这里处理
            $this->_data['msg'] = 'cannot use here';
            return;
        } else if ($itemId >= 9000 && $itemId <= 9999) { // 宠物道具
            // 不在这里处理
            $this->_data['msg'] = 'cannot use here';
            return;
        } else {
            if ($itemId >= 4000 && $itemId <= 4998) { // 食物(4999测试用,跳过)
                $feedTimes = $this->_userModel->hGet('feed_times');
                if ($feedTimes === false || $feedTimes <= 0) { // 今天可吃食物剩余次数
                    $this->_data['msg'] = 'reach max feed times';
                    return;
                }
            }
            if (Bag::decrItem($itemId, $num) === false) { // 扣除物品失败
                $this->_data['msg'] = 'decr item error';
                return;
            }
            $itemDataModel = new ItemDataModel($itemId);
            $jsonStr = $itemDataModel->hGet('extra_info');
            if ($jsonStr === '') { // 额外信息为空,跳过
                continue;
            }
            $jsonArr = json_decode($jsonStr, true);
            if ($jsonArr === NULL) { // 解码失败
                $this->_data['msg'] = 'extra info decode error';
                return;
            }
            foreach ($jsonArr as $key => $val) {
                switch ($key) {
                    case 'energy':
                        User::updateEnergy($val);
                        if ($itemId != 4999) {
                            $this->_userModel->hIncrBy('feed_times', -1);
                        }
                        break;
                }
            }
        }
        // 写动作日志
        $dataArr = array(
            'val1' => $itemId, // 道具ID
            'val2' => $num, // 数量
            'val3' => 0, // 暂时没用
            'val4' => '', // 暂时没用
        );
        User::actionLog(User::ACTION_TYPE_USE_ITEM, $dataArr); // 写动作日志(使用道具)
        $this->_ret = 0;
    }

    /**
     * 出售道具
     * @param int $itemId 道具ID
     */
    public function sellItem($itemId) {
        if (!is_numeric($itemId) || $itemId < 5000 || $itemId > 5999) {
            $this->_data['msg'] = 'invalid item id';
            return;
        }
        if (Bag::decrItem($itemId, 1) === false) {
            $this->_data['msg'] = 'decr item fail';
            return;
        }
        Bag::incrItem(Common::ITEM_SILVER, 10);

        $this->_ret = 0;
    }

    /**
     * 兑换道具(收藏品)
     * @param int $groupId 兑换组ID
     */
    public function exchangeItem($groupId = NULL) {
        if (!is_numeric($groupId)) {
            $this->_data['msg'] = 'bad request';
            return;
        }
        $configArr = Common::getExchangeConfig($groupId);
        if ($configArr === false) {
            $this->_data['msg'] = 'invalid group id';
            return;
        }
        $itemIdArr = $configArr['item_ids'];
        foreach ($itemIdArr as $itemId) { // 道具数量检测
            $curNum = Bag::getItemNum($itemId);
            if ($curNum === false || $curNum < 1) { // 数量不足
                $this->_data['msg'] = 'item not enought';
                return;
            }
        }
        foreach ($itemIdArr as $itemId) { // 道具扣除
            Bag::decrItem($itemId, 1);
        }
        foreach ($configArr['reward'] as $item) {
            Bag::incrItem($item['id'], $item['num']);
        }
        User::updateCredit(User::CREDIT_COLLECTION_EXCHANGE, 1); // 更新收藏品兑换荣誉次数
        $this->_ret = 0;
    }

    /**
     * 获取收藏奖励
     * @param int $groupId
     */
    public function getCollectionReward($groupId = NULL) {
        if (!is_numeric($groupId)) {
            $this->_data['msg'] = 'bad request';
            return;
        }
        $flagModel = new FlagModel(NULL, FlagModel::TYPE_COLLECTION);
        $index = $groupId - 1;
        $val = $flagModel->getBit($index);
        if ($val == 1) {
            $this->_data['msg'] = 'already get';
            return;
        }
        $configArr = Common::getExchangeConfig($groupId);
        if ($configArr === false) {
            $this->_data['msg'] = 'invalid group id or config data error';
            return;
        }
        $itemIdArr = $configArr['item_ids'];
        $collectionModel = new CollectionModel();
        foreach ($itemIdArr as $itemId) { // 道具数量检测
            $curNum = Bag::getItemNum($itemId);
            if ($curNum === false || $curNum < 1) {
                $this->_data['msg'] = 'item not enought';
                return;
            }
        }
        foreach ($itemIdArr as $itemId) { // 道具数量扣除
            if (Bag::decrItem($itemId, 1) === false) {
                $this->_data['msg'] = 'decr item error';
                return;
            }
        }

        foreach ($configArr['reward'] as $item) {
            Bag::incrItem($item['id'], $item['num']);
        }
        $flagModel->setBit($index, 1);
        $this->_ret = 0;
    }

    /**
     * 购买金币道具
     * @param array $itemDataArr 道具数据数组
     * @param int $itemId 道具ID
     * @param int $num 购买数量
     * @param bool $needWear 是否穿上(仅用于服装道具,false=不穿上,true=穿上)
     */
    private function __buySilverItem($itemDataArr, $itemId, $num, $needWear) {
        $needSilver = $itemDataArr['silver'] * $num;
        $curSilver = $this->_userModel->hGet('silver');
        if ($needSilver <= 0 || $needSilver > $curSilver) { // 不出售或金币不够,直接返回
            $this->_data['msg'] = 'not sale or not enough silver';
            return;
        }
        if (Bag::decrItem(Common::ITEM_SILVER, $needSilver) === false) { // 扣钱失败,直接返回
            $this->_data['msg'] = 'decr gold error';
            return;
        }
        if ($itemId >= 2000 && $itemId <= 2999) { // 装饰
            if ($itemDataArr['extra_info']['category'] === 'pet') { // 宠物装饰
                if ($num > 1) { // 限制购买一个
                    Bag::incrItem(Common::ITEM_SILVER, $needSilver); // 钱回滚
                    $this->_data['msg'] = 'pet deco num must 1';
                    return;
                }
                $curNum = Bag::getItemNum($itemId);
                if ($curNum !== false && $curNum > 0) { // 背包中已经有一个,不能再买
                    Bag::incrItem(Common::ITEM_SILVER, $needSilver); // 钱回滚
                    $this->_data['msg'] = 'already exists one';
                    return;
                }
            }
        } else if ($itemId >= 8000 && $itemId <= 8999) { // 套装(先将部件添加到背包,再换装)
            $itemIdArr = Common::getSuitConfig($itemId); // 获取套装的部件
            foreach ($itemIdArr as $itemId2) {
                if (Bag::incrItem($itemId2, 1) && $needWear) {
                    $this->__wearSingleItem($itemId2);
                }
            }
            $this->_ret = 0;
            return; // 不再需要后续操作,直接返回
        } else if ($itemId >= 9000 && $itemId <= 9999) { // 宠物道具(直接进宠物列表,不进背包)
            if ($num != 1) { // 数量限制为1
                Bag::incrItem(Common::ITEM_SILVER, $needSilver); // 钱回滚
                $this->_data['msg'] = 'bad request';
                return;
            }
            $petId = Pet::addPet($itemId);
            if ($petId === false) { // 添加失败
                Bag::incrItem(Common::ITEM_SILVER, $needSilver); // 钱回滚
                $this->_data['msg'] = 'add pet error';
                return;
            }
            $this->_data['pet_id'] = $petId; // 成功时返回宠物ID
            $this->_ret = 0;
            return; // 不再需要后续操作,直接返回
        }
        if (Bag::incrItem($itemId, $num) === false) { // 进背包
            Bag::incrItem(Common::ITEM_SILVER, $needSilver); // 钱回滚
            $this->_data['msg'] = 'incr item error';
            return;
        }
        if ($itemId >= 1000 && $itemId <= 1999) { // 服装道具(单件)
            $needWear && $this->__wearSingleItem($itemId, $itemDataArr);
        }
        $this->_ret = 0;
    }

    /**
     * 购买FH币道具
     * @param array $itemDataArr 道具数据数组
     * @param int $itemId 道具ID
     * @param int $num 购买数量
     * @param bool $needWear 是否替换现有的(仅用于装饰道具,false=不替换,true=替换)
     */
    private function __buyGoldItem($itemDataArr, $itemId, $num, $needWear) {
        $needGold = $itemDataArr['gold'] * $num;
        $curGold = $this->_userModel->hGet('gold');
        if ($needGold <= 0 || $needGold > $curGold) { // 不出售或FH币不够,直接返回
            $this->_data['msg'] = 'not sale or not enough gold';
            return;
        }
        if (Bag::decrItem(Common::ITEM_GOLD, $needGold) === false) { // 扣钱失败,直接返回
            $this->_data['msg'] = 'decr gold error';
            return;
        }
        if ($itemId >= 2000 && $itemId <= 2999) { // 装饰
            if ($itemDataArr['extra_info']['category'] === 'pet') { // 宠物装饰
                if ($num > 1) { // 限制购买一个
                    Bag::incrItem(Common::ITEM_GOLD, $needGold); // 钱回滚
                    $this->_data['msg'] = 'pet deco num must 1';
                    return;
                }
                $curNum = Bag::getItemNum($itemId);
                if ($curNum !== false && $curNum > 0) { // 背包中已经有一个,不能再买
                    Bag::incrItem(Common::ITEM_GOLD, $needGold); // 钱回滚
                    $this->_data['msg'] = 'already exists one';
                    return;
                }
            }
        } else if ($itemId >= 8000 && $itemId <= 8999) { // 套装(先将部件添加到背包,再换装)
            $itemIdArr = Common::getSuitConfig($itemId); // 获取套装的部件
            foreach ($itemIdArr as $itemId2) {
                if (Bag::incrItem($itemId2, 1) && $needWear) {
                    $this->__wearSingleItem($itemId2);
                }
            }
            $this->_ret = 0;
            return; // 不再需要后续操作,直接返回
        } else if ($itemId >= 9000 && $itemId <= 9999) { // 宠物道具(直接进宠物列表,不进背包)
            if ($num != 1) { // 数量限制为1
                Bag::incrItem(Common::ITEM_GOLD, $needGold); // 钱回滚
                $this->_data['msg'] = 'bad request';
                return;
            }
            $petId = Pet::addPet($itemId);
            if ($petId === false) { // 添加失败
                Bag::incrItem(Common::ITEM_GOLD, $needGold); // 钱回滚
                $this->_data['msg'] = 'add pet error';
                return;
            }
            $this->_data['pet_id'] = $petId; // 成功时返回宠物ID
            $this->_ret = 0;
            return; // 不再需要后续操作,直接返回
        }
        if (Bag::incrItem($itemId, $num) === false) { // 进背包
            Bag::incrItem(Common::ITEM_GOLD, $needGold); // 钱回滚
            $this->_data['msg'] = 'incr item error';
            return;
        }
        if ($itemId >= 1000 && $itemId <= 1999) { // 服装道具(单件)
            $needWear && $this->__wearSingleItem($itemId, $itemDataArr);
        }
        // 写消费日志(消费)
        $dataArr = array(
            'type' => 1, // 消费
            'num' => -$needGold,
            'remain' => $this->_userModel->hGet('gold'),
        );
        User::log(User::LOG_TYPE_GOLD_LOG, $dataArr);
        $this->_ret = 0;
    }

    /**
     * 穿戴单个道具
     * @param int $itemId 道具ID
     * @param array $itemDataArr 道具数据数组
     * @return int 0表示操作成功,非0表示操作失败
     */
    private function __wearSingleItem($itemId, $itemDataArr = NULL) {
        if ($itemDataArr === NULL) {
            $itemDataModel = new ItemDataModel($itemId);
            $itemDataArr = $itemDataModel->hGetAll();
            if (empty($itemDataArr)) { // 没取到
                return -1;
            }
            $extraInfoStr = $itemDataArr['extra_info'];
            $extraInfoArr = json_decode($extraInfoStr, true);
            if ($extraInfoArr === NULL) { // json_decode fail
                return -2;
            }
            $itemDataArr['extra_info'] = $extraInfoArr;
        }
        $part = $itemDataArr['extra_info']['category'];
        if (!in_array($part, array('head', 'body_up', 'hand', 'body_down', 'socks', 'foot', 'other'))) { // 部位判断
            return -3;
        }
        $dressModel = new DressModel();
        if ($dressModel->hSet($part, $itemId) === false) {
            return -4;
        }
        return 0;
    }
}
?>