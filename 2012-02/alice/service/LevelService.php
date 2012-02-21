<?php
/**
 * 关卡服务类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class LevelService extends Service {
    /**
     * 获取关卡相关数据
     * @param int $areaId 地区ID
     */
    public function getConfig($areaId = NULL) {
        if (!is_numeric($areaId)) {
            $this->_data['msg'] = 'invalid area id';
            return;
        }
        $dataArr = array(
            'area_id' => $areaId,
            'node' => '',
            'mode' => '',
            'finished' => '',
            'current' => '',
            'fail' => '', // 最后失败的关卡ID
            'reward' => '',
            'sys_items' => '',
            'chest' => '',
            'item7906' => 0, // 道具7906的个数
            'tip_times' => 0,
        );
        $levelModel = new LevelModel();
        if ($levelModel->hMset($dataArr) === false) {
            $this->_data['msg'] = 'hMset fail';
            return;
        }
        // 挑战模式下闯关分数
        $levelPointModel = new LevelPointModel(NULL, $areaId);
        $levelPointDataArr = $levelPointModel->hGetAll();
        $maxNode = -1;
        foreach ($levelPointDataArr as $node => $point) {
            if ($node > $maxNode) {
                $maxNode = $node;
            }
        }
        $tempArr = array();
        for ($i = 0; $i <= $maxNode; ++$i) {
            $tempArr[] = isset($levelPointDataArr[$i]) ? intval($levelPointDataArr[$i]) : -1;
        }
        // 最后一次闯关模式记录
        $lastModeModel = new LastModeModel();
        $lastMode = $lastModeModel->hGet($areaId, 0);
        $this->_data = array(
            'level_point' => $tempArr,
            'last_mode' => $lastMode,
        );
        $this->_ret = 0;
    }

    /**
     * 显示虚线
     */
    public function showDashed() {
        $levelModel = new LevelModel();
        $tipTimes = $levelModel->hIncrBy('tip_times', 1);
        if ($tipTimes === false) {
            $this->_data['msg'] = 'hIncrBy fail';
            return;
        }
        $areaId = $levelModel->hGet('area_id');
        $needNum = ($areaId - 1) * (50 * $areaId - 20 * $tipTimes - 50);
        if ($needNum > 0 && Bag::decrItem(Common::ITEM_SILVER, $needNum) === false) {
            $levelModel->hIncrBy('tip_times', -1);
            $this->_data['msg'] = 'decr item fail';
            return;
        }
        $this->_ret = 0;
    }

    /**
     * 开始关卡
     * @param int $areaId 地区ID[1,99999]
     * @param int $node 节点编号[0,50]
     * @param int $mode 模式(0=普通,1=挑战,2=隐藏)
     * @param int $type 类型(0=正常体力闯关,1=祝福点复活,2=金币复活,3=?,4=体力复活)
     */
    public function beginLevel($areaId = NULL, $node = NULL, $mode = NULL, $type = NULL) {
        if (!is_numeric($areaId)) {
            $this->_data['msg'] = 'invalid area id';
            return;
        }
        if (!is_numeric($node) || $node < 0 || $node > 50) {
            $this->_data['msg'] = 'invalid node';
            return;
        }
        if (!in_array($mode, array(0, 1, 2))) {
            $this->_data['msg'] = 'invalid mode';
            return;
        }
        if (!in_array($type, array(0, 1, 2, 4))) {
            $this->_data['msg'] = 'invalid type';
            return;
        }
        $levelModel = new LevelModel();
        $dataArr = $levelModel->hGetAll();
        if (empty($dataArr)) { // 没取到
            $this->_data['msg'] = 'unknown error';
            return;
        }
        if ($dataArr['area_id'] != $areaId) { // 地区不匹配
            $this->_data['msg'] = 'area id not match';
            return;
        }
        $isRetry = false;
        if ($type != 0 && $dataArr['fail'] != '') { // 闯关失败复活的情况
            $newDataArr = array(
                'finished' => '',
                'fail' => '',
            );
            if ($levelModel->hMset($newDataArr) === false) {
                $this->_data['msg'] = 'unknown error';
                return;
            }
            $isFirstLevel = true;
            $finishedArr = array();
            $isRetry = true;
        } else if ($dataArr['finished'] === '') {
            $isFirstLevel = true;
            $finishedArr = array();
        } else {
            $isFirstLevel = false;
            $finishedArr = explode(',', $dataArr['finished']);
        }
        $node = $isFirstLevel ? $node : $dataArr['node'];
        $mode = $isFirstLevel ? $mode : $dataArr['mode'];
        // 计算关卡数
        $levelCount = 1;
        if (count($finishedArr) >= $levelCount) { // 关卡数不匹配
            $this->_data['msg'] = 'level count not match';
            return;
        }
        $hasAnimate = false;
        // 不同模式解锁判断
        if ($mode == 0) { // 普通模式
            $nodeCount = Common::getNodeCountByAreaId($areaId);
            $progress = $this->_userModel->hGet('progress');
            list($areaId2, $node2, $flag2) = explode('-', $progress);
            if ($node2 == ($nodeCount - 1)) { // 最后一个节点,判断等级
                // 判断等级够不够
                $grade = Common::getGradeByExp($this->_userModel->hGet('exp'));
                $needGrade = Common::getNeedGradeByAreaId($areaId);
                if ($grade < $needGrade) { // 等级不够
                    $this->_data['msg'] = 'grade is lower than need grade';
                    return;
                }
            }
            if ($node2 == 0 && $flag2 == 0) { // 第一个节点
                $hasAnimate = 100;
            } else if ($node2 == ($nodeCount - 1) && count($finishedArr) == ($levelCount - 1)) { // 最后一个节点最后一关
                $hasAnimate = 101;
            }
            if ($areaId2 < $areaId || ($areaId2 == $areaId && $node2 < $node)) { // 判断节点是否已解锁
                $this->_data['msg'] = 'node unlock0';
                return;
            }
            if ($isFirstLevel && $areaId == $areaId2 && $node == $node2 && $flag2 == 0) { // 修改标志
                $newProgress = implode('-', array($areaId2, $node2, 1));
                $this->_userModel->hSet('progress', $newProgress);
            }
        } else if ($mode == 1) { // 挑战模式
            $levelPointModel = new LevelPointModel(NULL, $areaId);
            $point = $levelPointModel->hGet($node, false);
            if ($point === false) { // 未解锁
                $this->_data['msg'] = 'node unlock1';
            }
            if ($point == -1) {
                $levelPointModel->hSet($node, 0);
            }
        } else if ($mode == 2) { // 隐藏模式
            // 关联的解锁道具ID
            $refItemId = 6001 + ($areaId - 1) * 3 + $node;
            $hiddenLevelModel = new HiddenLevelModel(NULL, HiddenLevelModel::TYPE_UNLOCKED);
            if (!$hiddenLevelModel->hExists($refItemId)) {
                $this->_data['msg'] = 'node unlock2';
                return;
            }
            $hiddenLevelModel = new HiddenLevelModel(NULL, HiddenLevelModel::TYPE_FINISHED);
            if ($hiddenLevelModel->sIsMember($refItemId)) {
                $this->_data['msg'] = 'already finished';
                return;
            }
        } else {
            $this->_data['msg'] = 'invalid mode';
            return;
        }
        if ($isRetry) { // 重试
            $levelId = $dataArr['fail'];
        } else {
            if ($mode == 0 || $mode == 1) {
                $levelSetModel = new LevelSetModel($areaId, $node, 0); // 挑战模式共用普通模式的关卡
            } else if ($mode == 2) {
                $levelSetModel = new LevelSetModel($areaId, $node, 2);
            }
            $levelIdArr = $levelSetModel->sMembers();
            if (empty($levelIdArr)) { // 关卡集合数据未获取到或关卡集合数据为空
                $this->_data['msg'] = 'cannot get level set data';
                return;
            }
            // 未完成的关卡
            $levelIdArr = array_diff($levelIdArr, $finishedArr);
            // 获取当前关卡编号
            if ($node % 5 == 4) { // 大节点,随机取一关
                $levelId = $levelIdArr[array_rand($levelIdArr)];
            } else {
                $levelId = array_shift($levelIdArr);
            }
        }

        // 调试指定关卡
        $testLevelId = $this->_userModel->hGet('level_id', false);
        if (is_numeric($testLevelId)) {
            $levelId = $testLevelId;
        }
        if ($levelId === NULL) { // 关卡集合中关卡太少了
            $this->_data['msg'] = 'not enough level id in level set';
            return;
        }
        if ($isFirstLevel) { // 第一小关,需进行体力值(祝福/金币)或魅力值检测
            if ($mode == 0 || $mode == 1) {// 普通&挑战模式,判断体力
                if ($type == 0 || $type == 4) {
                    $curEnergy = User::getEnergy();
                    $needEnergy = Common::getNeedEnergyByExp($this->_userModel->hGet('exp'));
                    if ($curEnergy < $needEnergy) { // 没有足够的体力
                        $this->_data['energy'] = $curEnergy;
                        $this->_data['msg'] = 'no enough energy';
                        $this->_ret = UserException::ERROR_NOT_ENOUGH_ENERGY;
                        return;
                    }
                } else if ($type == 1) {
                    // 检查今天祝福复活剩余次数
                    $reviveTimes = $this->_userModel->hGet('revive_times1');
                    if ($reviveTimes <= 0) {
                        $this->_data['msg'] = 'no times';
                        return;
                    }
                    // 检查祝福值够不够
                    $benison = $this->_userModel->hGet('benison');
                    if ($benison <= 0) {
                        $this->_data['msg'] = 'not enough benison';
                        return;
                    }
                } else if ($type == 2) {
                    // 检查今天金币复活剩余次数
                    $reviveTimes = $this->_userModel->hGet('revive_times2');
                    if ($reviveTimes <= 0) {
                        $this->_data['msg'] = 'no times';
                        return;
                    }
                    // 检查金币够不够
                    $curSilver = Bag::getItemNum(Common::ITEM_SILVER);
                    if ($curSilver === false || $curSilver < 200) {
                        $this->_data['msg'] = 'not enough silver';
                        return;
                    }
                }
            } else if ($mode == 2) { // 隐藏模式,判断魅力
                $needCharm = $areaId * 10 + 15;
                $curCharm = $this->_userModel->hGet('charm');
                if ($curCharm < $needCharm) { // 没有足够的魅力
                    $this->_data['msg'] = 'no enough charm';
                    return;
                }
            }
        }
        // 创建关卡数据模型实例
        $levelDataModel = new LevelDataModel();
        $levelDataStr = $levelDataModel->hGet($levelId);
        if ($levelDataStr === false) { // 关卡数据没取到
            $this->_data['msg'] = 'get level data error';
            return;
        }
        $levelDataArr = json_decode($levelDataStr, true);
        if ($levelDataArr === NULL) { // 关卡数据解码失败
            $this->_data['msg'] = 'level data decode error';
            return;
        }
        // 记录关卡ID,以方便调试
        $levelDataArr['level_id'] = $levelId;
        // 奖励处理
        $rewardItemArr = array(); // 奖励道具数组
        if (!empty($levelDataArr['chest']) && is_array($levelDataArr['chest'])) { // 并且设置了宝箱数据
            // 获取宝箱数据
            $this->__getChestData($areaId, $node, $mode, $levelDataArr['chest'], $rewardItemArr, $levelId);
        } else {
            $levelDataArr['chest'] = array();
        }
        // 地图名称
        $mapName = sprintf('map_%d', $areaId);
        $mapDataModel = new MapDataModel();
        $mapDataStr = $mapDataModel->hGet($mapName);
        if ($mapDataStr === false) { // 地图数据没取到
            $this->_data['msg'] = 'get map data error';
            return;
        }
        $mapDataArr = json_decode($mapDataStr, true);
        if ($mapDataArr === NULL) { // 地图数据解码失败
            $this->_data['msg'] = 'map data decode error';
            return;
        }
        $newDataArr = array(
            'current' => $levelId,
            'fail' => '',
        );
        if (!empty($rewardItemArr)) {
            $newDataArr['reward'] = json_encode($rewardItemArr);
        } else {
            $newDataArr['reward'] = '';
        }
        if (!empty($levelDataArr['sys_items']) && is_array($levelDataArr['sys_items'])) {
            $sysItemStatArr = array();
            foreach ($levelDataArr['sys_items'] as $item) {
                $itemId = $item['id'];
                if (isset($sysItemStatArr[$itemId])) {
                    ++$sysItemStatArr[$itemId];
                } else {
                    $sysItemStatArr[$itemId] = 1;
                }
            }
            $newDataArr['sys_items'] = json_encode($sysItemStatArr);
        } else {
            $newDataArr['sys_items'] = '';
        }
        if ($isFirstLevel) {
            $newDataArr['node'] = $node;
            $newDataArr['mode'] = $mode;
            $newDataArr['chest'] = '';
            $newDataArr['item7906'] = 0;
            $newDataArr['tip_times'] = 0;
        }
        if ($levelModel->hMset($newDataArr) === false) { // 设置失败
            $this->_data['msg'] = 'unknown error';
            return;
        }
        if ($isFirstLevel) { // 第一小关,需要扣体力或魅力
            if ($mode == 0 || $mode == 1) { // 普通&挑战模式,扣体力
                switch ($type) {
                    case 0: // 体力
                    case 4:
                        if (User::updateEnergy(-1 * $needEnergy) === false) { //扣除体力失败
                            $this->_data['msg'] = 'decr energy fail';
                            return;
                        }
                        $dataArr = array(
                            'val1' => $needEnergy,
                            'val2' => 0, // 暂时没用
                            'val3' => 0, // 暂时没用
                            'val4' => '', // 暂时没用
                        );
                        User::actionLog(User::ACTION_TYPE_USE_ENERGY, $dataArr); // 写动作日志(使用体力)
                        break;
                    case 1: // 祝福
                        if ($this->_userModel->hIncrBy('benison', -1) === false) {
                            $this->_data['msg'] = 'decr benison fail';
                            return;
                        }
                        $this->_userModel->hIncrBy('revive_times1', -1);
                        break;
                    case 2: // 金币
                        if (Bag::decrItem(Common::ITEM_SILVER, 200) === false) {
                            $this->_data['msg'] = 'decr silver fail';
                            return;
                        }
                        $this->_userModel->hIncrBy('revive_times2', -1);
                        break;
                }
                // 更新最后一次闯关模式记录
                $lastModeModel = new LastModeModel();
                $lastModeModel->hSet($areaId, $mode);
            } else if ($mode == 2) { // 隐藏模式,扣魅力
                $hiddenLevelModel = new HiddenLevelModel(NULL, HiddenLevelModel::TYPE_UNLOCKED);
                $flag = $hiddenLevelModel->hGet($refItemId);
                if ($flag == 0) {
                    $hiddenLevelModel->hSet($refItemId, 1);
                }
                $this->_userModel->hIncrBy('charm', -1 * $needCharm);
            }
        }
        $returnArr = array_merge($levelDataArr, $mapDataArr);
        $returnArr2 = array();
        if ($hasAnimate) {
            $levelSetModel = new LevelSetModel($areaId, $hasAnimate, 0);
            $levelIdArr = $levelSetModel->sMembers();
            if (!empty($levelIdArr)) {
                $returnArr2 = json_decode($levelDataModel->hGet($levelIdArr[0]), true);
            }
        }
        $this->_data = array(
            'data' => $returnArr,
            'data2' => $returnArr2,
        );
        $this->_ret = 0;
    }

    /**
     * 完成关卡
     * @param int $areaId 地点ID
     * @param int $success 是否成功(0=失败,1=成功)
     * @param array $useItemArr 使用道具数组
     * @param array $sysItemArr 使用的系统道具数组
     * @param array $collectItemArr 收集道具数组
     * @param int $useGold 使用FH币复活(0=没使用,1=使用了,功能已取消)
     * @param array $petArr 宠物数组
     * @param int $refTaskId 关联任务ID(当身上有主线任务时,需要提供此参数,其他传-1)
     * @param int $finishPercent 完成度(仅挑战模式需要)
     * @param int $point 分数
     */
    public function finishLevel($areaId = NULL, $success = NULL, $useItemArr = NULL, $sysItemArr = NULL, $collectItemArr = NULL, $useGold = NULL, $petArr = NULL, $refTaskId = NULL, $finishPercent = NULL, $point = 0) {
        if (!is_numeric($areaId)) {
            $this->_data['msg'] = 'invalid area id';
            return;
        }
        if (!in_array($success, array(0, 1))) {
            $this->_data['msg'] = 'invalid success';
            return;
        }
        if (!is_array($useItemArr)) {
            $this->_data['msg'] = 'invalid use item array';
            return;
        }
        if (!is_array($sysItemArr)) {
            $this->_data['msg'] = 'invalid sys item array';
            return;
        }
        if (!is_array($collectItemArr)) {
            $this->_data['msg'] = 'invlaid collect item array';
            return;
        }
        if (!is_array($petArr)) {
            $this->_data['msg'] = 'invalid pet arr';
            return;
        }
        if (!is_numeric($refTaskId)) {
            $this->_data['msg'] = 'invalid ref task id';
            return;
        }
        $flagArr = array(
            1 => 1, 2 => 1, 5 => 1, 6 => 1, 7 => 1, 10 => 1, 11 => 1, 15 => 1, 16 => 1, 20 => 1,
        );
        $levelModel = new LevelModel();
        $dataArr = $levelModel->hGetAll();
        if (empty($dataArr)) { // 没取到
            $this->_data['msg'] = 'unknown error';
            return;
        }
        if ($dataArr['area_id'] != $areaId) { // 地区不匹配
            $this->_data['msg'] = 'area not match';
            return;
        }
        $levelId = $dataArr['current'];
        if ($levelId === '') {
            $this->_data['msg'] = 'data error';
            return;
        }
        $node = $dataArr['node'];
        $mode = $dataArr['mode'];
        $finishedArr = ($dataArr['finished'] === '') ? array() : explode(',', $dataArr['finished']);
        $rewardItemArr = ($dataArr['reward'] === '') ? array() : json_decode($dataArr['reward'], true);
        $allSysItemStatArr = ($dataArr['sys_items'] === '') ? array() : json_decode($dataArr['sys_items'], true);
        $item7906 = $dataArr['item7906'];
        // 计算关卡数
        $levelCount = 1;
        if (count($finishedArr) >= $levelCount) { // 已完成的关卡数大于等于设定的关卡数
            $this->_data['msg'] = 'level count not match';
            return;
        }
        $isFinalLevel = (count($finishedArr) + 1) == $levelCount; // 是否是最后一关
        $sysItemStatArr = array();
        foreach ($sysItemArr as $item) {
            $itemId = $item['item_id'];
            if (isset($sysItemStatArr[$itemId])) {
                ++$sysItemStatArr[$itemId];
            } else {
                $sysItemStatArr[$itemId] = 1;
            }
        }
        foreach ($sysItemStatArr as $itemId => $num) {
            if (!isset($allSysItemStatArr[$itemId]) || $num > $allSysItemStatArr[$itemId]) {
                $this->_data['msg'] = 'too much sys items';
                return;
            }
        }
        $has7903 = false;
        if ($success == 1 && $refTaskId != -1) { // 身上有主线任务,奖需求道具
            $needItem = $this->__getMainTaskNeedItemId($refTaskId);
            if ($needItem !== false) {
                foreach ($needItem as $item) {
                    $itemId = $item['id'];
                    $num = $item['num'];
                    if ($itemId == 7903) {
                        $has7903 = true;
                    } else if (isset($rewardItemArr[$itemId])) {
                        $rewardItemArr[$itemId] += $num;
                    } else {
                        $rewardItemArr[$itemId] = $num;
                    }
                }
            }
        }
        foreach ($collectItemArr as $collectItem) { // 收集道具添加前检测
            $itemId = $collectItem['item_id'];
            $num = $collectItem['num'];
            if (!is_numeric($num) || !isset($rewardItemArr[$itemId]) || $rewardItemArr[$itemId] < $num) { // 数据欺骗
                $this->_data['msg'] = 'reward item num too more';
                return;
            }
            if (substr($itemId, 0, 1) == 4 && $num >= 1) { // 闯关捡到至少1个体力值道具
                $flagArr[17] = 1;
            }
        }
        if (count($collectItemArr) < 3) { // 闯关获得的泡泡个数少于3个
            $flagArr[13] = 1;
        }
        foreach ($useItemArr as $useItem) { // 使用道具扣除前检测
            $itemId = $useItem['item_id'];
            $curNum = Bag::getItemNum($itemId);
            if ($curNum === false || $curNum < 1) { // 道具数量不足
                $this->_data['msg'] = 'not enough item';
                return;
            }
        }
        if ((count($useItemArr) + count($sysItemArr)) < 3) { // 闯关成功并且消耗的道具少于3个
            $flagArr[14] = 1;
        }
        foreach ($petArr as $pet) { // 使用宠物开心值扣减前检测
            $petModel = new PetModel(NULL, $pet['item_id']);
            if (!$petModel->exists()) {
                $this->_data['msg'] = 'pet not exists';
                return;
            }
            if (!isset($pet['happy']) || !is_numeric($pet['happy'])) {
                $this->_data['msg'] = 'invalid happy';
                return;
            }
            $curHappy = $petModel->hGet('happy');
            if ($curHappy < 45) {
                $this->_data['msg'] = 'pet happy not enough';
                return;
            }
        }
        foreach ($useItemArr as $useItem) { // 扣除使用的道具
            Bag::decrItem($useItem['item_id'], 1);
            // 写动作日志
            $dataArr = array(
                'val1' => $useItem['item_id'], // 道具ID
                'val2' => 1, // 数量
                'val3' => 0, // 暂时没用
                'val4' => '', // 暂时没用
            );
            User::actionLog(User::ACTION_TYPE_USE_ITEM, $dataArr); // 写动作日志(使用道具)
        }
//        $needCollectItemId = NULL; // 好友任务需要收集的道具ID
//        $taskModel = new TaskModel();
//        $activeHashKey = $taskModel->hGet('active', false); // 当前正在做的好友任务
//        if ($activeHashKey !== false) {
//            $friendTaskModel = new FriendTaskModel();
//            $jsonStr = $friendTaskModel->hGet($activeHashKey, false);
//            if ($jsonStr !== false) {
//                $jsonArr = json_decode($jsonStr, true);
//                $taskId = $jsonArr['task_id'];
//                $taskDataModel = new TaskDataModel($taskId);
//                $tempArr = $taskDataModel->hGetAll();
//                if (!empty($tempArr)) {
//                    $needStr = $tempArr['need'];
//                    $needArr = json_decode($needStr, true);
//                    $needCollectItemId = $needArr['items'][0]['id'];
//                }
//            }
//        }
        if ($success == 1) { // 闯关成功,获得的道具才进背包
            foreach ($collectItemArr as $collectItem) { // 收集的道具添加到背包
                $itemId = $collectItem['item_id'];
                $num = $collectItem['num'];
                if ($itemId == 7906) { // 挑战模式下特殊道具,不进背包
                    $item7906 += $num;
                } else {
                    Bag::incrItem($itemId, $num); // 添加到背包
//                    if ($needCollectItemId !== NULL && $itemId == $needCollectItemId && $friendTaskModel) {
//                        $temNum = $jsonArr['num'] + $num;
//                        $jsonArr['num'] = min(TaskModel::NEED_COLLECT_NUM, $temNum); // 收集数量处理
//                        $friendTaskModel->hSet($activeHashKey, json_encode($jsonArr));
//                    }
                }
            }
        }
        $delta = 1;
        foreach ($petArr as $pet) {
            $petModel = new PetModel(NULL, $pet['item_id']);
            $itemDataModel = new ItemDataModel($pet['item_id']);
            $extraInfoStr = $itemDataModel->hGet('extra_info', false);
            if ($extraInfoStr === false) {
                $this->_data['msg'] = 'cannot get extra info';
                return;
            }
            $extraInfoArr = json_decode($extraInfoStr, true);
            if ($extraInfoArr === NULL) {
                $this->_data['msg'] = 'cannot decode extra info';
                return;
            }
            $petModel->hIncrBy('happy', -1 * abs($extraInfoArr['use']));
            if (in_array($pet['item_id'], array(9005))) { // 使用了幸运类宠物
                $delta = 1.1;
            }
        }
        if ($success != 1) { // 如果失败,没有过关奖励
            if ($mode == 0 || $mode == 1) {
                $this->_userModel->hIncrBy('level_fail', 1);
            }
            if ($isFinalLevel) {
                User::updateCredit(User::CREDIT_LEVEL_FAIL, 1);
            }
            $newLevelData = array(
                'current' => '',
                'fail' => $levelId,
            );
            if ($levelModel->hMset($newLevelData) === false) {
                $this->_data['msg'] = 'unknown error1';
                return;
            }
            switch ($mode) {
                case 0:
                    Stat::userDayIncr('mode_0_fail');
                    break;
                case 1:
                    Stat::userDayIncr('mode_1_fail');
                    break;
                case 2:
                    Stat::userDayIncr('mode_2_fail');
                    break;
            }
            $this->_ret = 0;
            return;
        }
        $finishedArr[] = $levelId;
        $newLevelData = array(
            'finished' => implode(',', $finishedArr),
            'current' => '',
            'fail' => '',
        );
        if ($levelModel->hMset($newLevelData) === false) {
            $this->_data['msg'] = 'unknown error2';
            return;
        }
        if ($mode == 0 || $mode == 1) {
            $rewardArr = array();
            $grade = Common::getGradeByExp($this->_userModel->hGet('exp'));
            $rewardExp = Common::getLevelRewardExp($areaId, $node, $grade);
            if ($mode == 1) {
                $rewardExp = round($rewardExp * 1.6);
            }
            // 更新分数
            $topPointModel = new TopPointModel(NULL, $areaId, $mode);
            $curPoint = $topPointModel->hGet($node, -1);
            if ($point > $curPoint && $point <= 45000) {
                $topPointModel->hSet($node, $point);
            }
            if ($point > 0 && $point <= 45000) {
                // 积分奖励经验
                $pointRewardExp = round($point / 135000 * $rewardExp);
                $rewardArr['point'] = array('id' => 7902, 'num' => $pointRewardExp);
            }
            if ($point > 10000) { // 闯关评价分数高于10000
                $flagArr[8] = 1;
                $flagArr[12] = 1;
                $flagArr[19] = 1;
            }
            if ($rewardExp < 50) { // 闯关获得的经验少于50
                $flagArr[3] = 1;
                $flagArr[18] = 1;
            }
            $rewardArr['items'] = array(
                array('id' => 7901, 'num' => Common::getLevelRewardSilver($areaId)),
                array('id' => 7902, 'num' => $rewardExp),
            );
        } else if ($mode == 2) {
            $rewardArr = array();
            $rewardArr['items'] = array();
        }
        if ($has7903) {
            if (isset($rewardArr['items'])) {
                $rewardArr['items'][] = array(
                    'id' => 7903,
                    'num' => 1,
                );
            } else {
                $rewardArr['items'][0] = array(
                    'id' => 7903,
                    'num' => 1,
                );
            }
        }
        // 保存7906道具的拾取数量
        $levelModel->hSet('item7906', $item7906);
        $nodeCount = Common::getNodeCountByAreaId($areaId);
        if ($isFinalLevel) { // 最后一关相关处理
            if ($mode == 0) { // 普通模式
                $progress = $this->_userModel->hGet('progress');
                list($areaId2, $node2, $flag2) = explode('-', $progress);
                if ($areaId2 == $areaId && $node2 == $node) {
                    if ($node < ($nodeCount - 1)) { // 非最后节点
                        ++$node2;
                        $flag2 = 0;
                    } else { // 最后节点
                        ++$areaId2;
                        $node2 = 0;
                        $flag2 = 0;
                        // 解锁当前地区的挑战模式
                        $levelPointModel = new LevelPointModel(NULL, $areaId);
                        if (!$levelPointModel->hExists(0) && $levelPointModel->hSetNx(0, -1) === false) {
                            $this->_data['msg'] = 'hSetNx fail1';
                            return;
                        }
                    }
                    $newProgress = implode('-', array($areaId2, $node2, $flag2));
                    $this->_userModel->hSet('progress', $newProgress);
                }
            } else if ($mode == 1) { // 挑战模式
                $levelPointModel = new LevelPointModel(NULL, $areaId);
                $point = $levelPointModel->hGet($node);
                if ($point == 0) { // 开始关卡的时候，已经将值更改为0
                    if ($node < ($nodeCount - 1)) { // 非最后节点
                        if (!$levelPointModel->hExists($node + 1) && $levelPointModel->hSetNx($node + 1, -1) === false) {
                            $this->_data['msg'] = 'hSetNx fail2';
                            return;
                        }
                    }
                }
                if ($item7906 >= 5 * $levelCount) {
                    $newPoint = 3;
                    // 更新每日任务进度
                    User::updateDayTask(9);
                } else if ($item7906 >= 3 * $levelCount) {
                    $newPoint = 2;
                } else if ($item7906 >= 1 * $levelCount) {
                    $newPoint = 1;
                } else {
                    $newPoint = 0;
                }
                if ($newPoint >= 3) { // 挑战关卡获得三星及以上评价
                    $flagArr[4] = 1;
                    $flagArr[9] = 1;
                }
                if ($newPoint > $point) { // 更新分数
                    $levelPointModel->hSet($node, $newPoint);
                }
            } else if ($mode == 2) { // 隐藏模式
                // 关联的解锁道具ID
                $refItemId = 6001 + ($areaId - 1) * 3 + $node;
                $hiddenLevelModel = new HiddenLevelModel(NULL, HiddenLevelModel::TYPE_FINISHED);
                $hiddenLevelModel->sAdd($refItemId);
            } else {
                $this->_data['msg'] = 'mode error';
                return;
            }
            User::updateCredit(User::CREDIT_LEVEL_CHALLENGE, 1); // 更新挑战关卡荣誉次数
            // 称号奖励处理
            $titleId = $this->_userModel->hGet('title');
            if (in_array($titleId, array(1, 2, 3, 4, 21, 22, 23, 24))) {
                User::giveTitleReward($titleId);
            }
            $newLevelData = array(
                'node' => $node + 1,
                'finished' => '',
                'current' => '',
                'fail' => '',
                'reward' => '',
                'sys_items' => '',
            );
            // 翻牌处理
            if (rand(1, 100) <= 20) {
                $chestDataArr = array();
                $hackArr = array();
                while (count($chestDataArr) < 5) {
                    $tempArr = Common::getFinalLevelChestConfig();
                    if (!in_array($tempArr['id'], $hackArr)) {
                        $chestDataArr[] = $tempArr;
                        $hackArr[] = $tempArr['id'];
                    }
                }
                $count = 2;
                $posArr = array_rand($chestDataArr, $count);
                $rewardArr['chest'] = $chestDataArr;
                $rewardArr['pos'] = $posArr;
                $dataArr = array(
                    'chest' => $chestDataArr,
                    'pos' => $posArr,
                );
                $newLevelData['chest'] = json_encode($dataArr);
            }
            $levelModel->hMset($newLevelData);
        }
        if ($mode == 1 && $finishPercent == ($nodeCount - 1)) {// 挑战模式完成度100%,给金杯和额外金币奖励
            $levelPointModel = new LevelPointModel(NULL, $areaId);
            $pointArr = $levelPointModel->hGetAll();
            if (count($pointArr) == $nodeCount) {
                $isAllFinished = true;
                foreach ($pointArr as $val) {
                    if ($val != 3) {
                        $isAllFinished = false;
                        break;
                    }
                }
                if ($isAllFinished) { // 挑战关卡完成度100%
                    $rewardArr['extra'] = array(
                        array('id' => $areaId + 2800, 'num' => 1), // 奖杯
                        array('id' => 7901, 'num' => 3000 + ($areaId - 1) * 1000),
                    );
                    // 设置奖杯标志位
                    $flagModel = new FlagModel($this->_userId, FlagModel::TYPE_CUP);
                    $flagModel->setBit($areaId - 1, 1);
                }
            }
        }
        // 计算精灵技能加成
        Effect::calcSpiritSkillEffect($flagArr, $rewardArr);
        foreach ($rewardArr as $key => $val) { // 关卡奖励处理
            if ($key === 'items') { // 正常奖励
                foreach ($val as $item) {
                    Bag::incrItem($item['id'], $item['num']);
                }
            } else if ($key === 'point') { // 积分奖励
                Bag::incrItem($val['id'], $val['num']);
            } else if ($key === 'extra') { // 额外奖励
                foreach ($val as $item) {
                    Bag::incrItem($item['id'], $item['num']);
                }
            }
        }
        if ($mode == 0 || $mode == 1) {
            $this->_userModel->hIncrBy('level_success', 1);
        }
        switch ($mode) { // 成功闯关数统计
            case 0:
                Stat::userDayIncr('mode_0_success');
                break;
            case 1:
                Stat::userDayIncr('mode_1_success');
                // 更新每日任务进度
                User::updateDayTask(7);
                break;
            case 2:
                Stat::userDayIncr('mode_2_success');
                // 更新每日任务进度
                User::updateDayTask(6);
                break;
        }
        // 更新每日任务进度
        User::updateDayTask(4);
        $this->_data = $rewardArr;
        $this->_ret = 0;
    }

    /**
     * 获取翻牌奖励
     * @param int $num 翻的张数,普通用户翻第二张需要500金币
     */
    public function getChestReward($num) {
        if (!in_array($num, array(1, 2))) {
            $this->_data['msg'] = 'invalid num';
            return;
        }
        $levelModel = new LevelModel();
        $jsonStr = $levelModel->hGet('chest');
        if ($jsonStr === false) {
            $this->_data['msg'] = 'bad request';
            return;
        }
        if ($num == 2) {
            $curNum = Bag::getItemNum(Common::ITEM_SILVER);
            if ($curNum === false || $curNum < 500) {
                $this->_data['msg'] = 'not enough silver';
                return;
            }
            $sns = App::getSNS();
            $userInfoArr = $sns->getUserInfo();
            if (!isset($userInfoArr['is_vip']) || !$userInfoArr['is_vip']) { // 不是VIP,翻第二张牌需要扣钱
                Bag::decrItem(Common::ITEM_SILVER, 500);
            }
        }
        $jsonArr = json_decode($jsonStr, true);
        foreach ($jsonArr['pos'] as $pos) {
            Bag::incrItem($jsonArr['chest'][$pos]['id'], $jsonArr['chest'][$pos]['num']);
            if ($num != 2) {
                break;
            }
        }
        $levelModel->hSet('chest', '');
        $this->_ret = 0;
    }

    /**
     * 获取宝箱数据
     * @param int $areaId 地区ID
     * @param int $node 节点编号[0,50]
     * @param int $mode 模式
     * @param &array $allChestArr 关卡数据数组
     * @param &array $rewardItemArr 奖励道具数组
     * @param int $levelId 关卡ID
     */
    private function __getChestData($areaId, $node, $mode, &$allChestArr, &$rewardItemArr, $levelId) {
        $tempArr = array();
        if ($mode == 0 || $mode == 1) {
            $itemLevelArr = Common::getLevelRewardItemLevel($areaId);
            if ($mode == 0 && $areaId == 1 && in_array($node, array(1, 4, 6, 9, 12))) { // 指定关卡出现固定图鉴
                array_unshift($itemLevelArr, 888);
            }
            if ($mode == 0) { // 隐藏关卡解锁道具
                $hiddenLevelUnlockItem = Common::randHiddenLevelUnlockItem($areaId);
                if ($hiddenLevelUnlockItem !== false) {
                    $itemLevelArr[] = 13;
                }
            }
            if ($mode == 1) {
                $itemLevelArr[] = 12;
                $itemLevelArr[] = 12;
                $itemLevelArr[] = 12;
                $itemLevelArr[] = 12;
                $itemLevelArr[] = 12;
            }
        } else if ($mode == 2) {
            if ($node == 0) {
                $itemLevelArr = array(2, 2, 2, 2, 2, 2, 2, 2);
            } else if ($node == 1) {
                $itemLevelArr = array(3, 3, 3, 3, 3, 3, 3, 3);
            } else if ($node == 2) {
                $itemLevelArr = array(2, 2, 2, 2, 3, 3, 3, 3, 10, 14);
                shuffle($itemLevelArr);
            }
        }
        foreach ($itemLevelArr as $itemLevel) {
            // 选择一个道具
            if ($mode == 0 || $mode == 1) {
                if ($itemLevel == 888) { // 收藏品
                    $itemIdArr = array(
                        1 => 5001,
                        4 => 5002,
                        6 => 5003,
                        9 => 5004,
                        12 => 5005,
                    );
                    $item = array('id' => $itemIdArr[$node], 'num' => 1);
                    $itemLevel = 7;
                } else if ($itemLevel == 10) { // 合成材料
                    $item = Common::getLevelRewardMaterialItem($areaId, $node + 1); // 此函数中的节点编号从1开始,所以这里要+1
                } else if ($itemLevel == 13) { // 隐藏关卡解锁道具
                    $item = array('id' => $hiddenLevelUnlockItem, 'num' => 1);
                } else if ($itemLevel >= 1000) { // 任务道具
                    $item = $this->__getTaskItem($areaId, $itemLevel);
                    $itemLevel = 1;
                } else {
                    $item = Common::getLevelRewardItem($itemLevel, $areaId);
                }
                if (empty($item) || !isset($item['id'])) { // 没获取到道具
                    continue;
                }
            } else if ($mode == 2) {
                if ($node == 0) {
                    $item = array('id' => 7901, 'num' => 5 * $areaId * $areaId + 75 * $areaId + 170);
                } else if ($node == 1) {
                    $item = array('id' => 7902, 'num' => $areaId * 30 + 50);
                } else if ($node == 2) {
                    if ($itemLevel == 2) {
                        $item = array('id' => 7901, 'num' => $areaId * 50 + 250);
                    } else if ($itemLevel == 3) {
                        $item = array('id' => 7902, 'num' => $areaId * 40 + 60);
                    } else if ($itemLevel == 10) {
                        $item = array('id' => 7205, 'num' => 1);
                    } else if ($itemLevel == 14) {
                        $item = array('id' => 7904, 'num' => $areaId * 5 + 10);
                    }
                }
            }
            // 随机选择一个宝箱
            $chestDataArr = $this->__randChest($itemLevel, $allChestArr);
            if (empty($chestDataArr)) { // 宝箱不足
                continue;
            }
            $chestDataArr['item_id'] = $item['id'];
            $chestDataArr['num'] = $item['num'];
            $tempArr[] = $chestDataArr;
            if (isset($rewardItemArr[$chestDataArr['item_id']])) {
                $rewardItemArr[$chestDataArr['item_id']] += $chestDataArr['num'];
            } else {
                $rewardItemArr[$chestDataArr['item_id']] = $chestDataArr['num'];
            }
        }
        // 七夕情人节活动(25% 红玫瑰*1,5% 蓝玫瑰*1)
        if (defined('FESTIVAL_7_7_FLAG') && FESTIVAL_7_7_FLAG == 1) {
            $itemArr = array();
            if (rand(1, 100) <= 25) {
                $itemArr[7108] = 1;
            }
            if (rand(1, 100) <= 5) {
                $itemArr[7109] = 1;
            }
            foreach ($itemArr as $itemId => $num) {
                $chestDataArr = $this->__randChest(1, $allChestArr); // 固定用Lv1的宝箱
                if (!empty($chestDataArr)) { // 宝箱数不够
                    $chestDataArr['item_id'] = $itemId;
                    $chestDataArr['num'] = $num;
                    $tempArr[] = $chestDataArr;
                    if (isset($rewardItemArr[$chestDataArr['item_id']])) {
                        $rewardItemArr[$chestDataArr['item_id']] += $chestDataArr['num'];
                    } else {
                        $rewardItemArr[$chestDataArr['item_id']] = $chestDataArr['num'];
                    }
                }
            }
        }
        $allChestArr = $tempArr;
    }

    /**
     * 选择宝箱
     * @param int $chestLevel 宝箱等级(1-12)
     * @param &array $allChestArr 所有宝箱数组
     * @return array/NULL
     */
    private function __randChest($chestLevel, &$allChestArr) {
        $key = sprintf("lv%d", $chestLevel);
        if (isset($allChestArr[$key])) {
            shuffle($allChestArr[$key]); // 打乱顺序
            $ret = array_shift($allChestArr[$key]);
            if ($ret !== NULL) {
                return $ret;
            }
        }
        return NULL;
    }

    /**
     * 获取任务道具
     * @param int $areaId 地区ID
     * @param int $type 类型
     * @return array/NULL
     */
    private function __getTaskItem($areaId, $type) {
        $taskId = NULL;
        $taskModel = new TaskModel();
        if ($type == 1001) {
            // 当前正在做的好友任务
            $hashKey = $taskModel->hGet('active', false);
            if ($hashKey !== false && strpos($hashKey, ':') !== false) {
                list($friendUserId, $taskId) = explode(':', $hashKey);
            }
        } else if ($type == 1002) {
            // 发布任务
            $jsonStr = $taskModel->hGet('temp', false);
            if ($jsonStr !== false) {
                $jsonArr = json_decode($jsonStr, true);
                if ($jsonArr != NULL) {
                    $taskId = $jsonArr['task_id'];
                }
            }
        } else if ($type == 1003) {
            // NPC任务(支线任务)
            $unfinishedTaskModel = new UnfinishedTaskModel();
            $tempArr = $unfinishedTaskModel->sMembers();
            $taskId = array_shift($tempArr);
        }
        if ($taskId === NULL) {
//            if ($type == 1002) {
//                $itemId = Common::getLevelTaskItem($areaId);
//                if ($itemId === false) { // 没取到
//                    return NULL;
//                }
//                return array('id' => $itemId, 'num' => 1);
//            }
            return NULL;
        }
        // 任务道具处理
        $taskDataModel = new TaskDataModel($taskId);
        $taskDataArr = $taskDataModel->hGetAll();
        if (empty($taskDataArr)) { // 没取到,跳过
            return NULL;
        }
        if ($taskDataArr['area_id'] != 0 && $taskDataArr['area_id'] != $areaId) { // 如果当前闯关地区跟任务所在地区不同,则随机取该地区的道具
            $item = Common::getLevelRewardItem(1, $areaId);
            if ($item === false) { // 没取到
                return NULL;
            }
            return $item;
        }
        $needStr = $taskDataArr['need'];
        $needArr = json_decode($needStr, true);
        if ($needArr === NULL) {
            return NULL;
        }
        if (!isset($needArr['items']) || count($needArr['items']) === 0) { // 任务数据设置不正确
            return NULL;
        }
        $maxIndex = count($needArr['items']) - 1;
        $index = rand(0, $maxIndex);
        $item = $needArr['items'][$index];
        if ($item['id'] == 7903) {
            return NULL;
        } else {
            $item['num'] = 1;
            return $item;
        }
    }

    /**
     * 获取主线任务需求道具
     * @param int $taskId 任务ID
     * @return array/false
     */
    private function __getMainTaskNeedItemId($taskId) {
        $unfinishedTaskModel = new UnfinishedTaskModel();
        if (!$unfinishedTaskModel->sIsMember($taskId)) { // 不存在未完成任务集合中
            return false;
        }
        $taskDataModel = new TaskDataModel($taskId);
        $taskDataArr = $taskDataModel->hGetAll();
        if (empty($taskDataArr)) { // 任务定义数据未取到
            return false;
        }
        $type = $taskDataArr['type'];
        if ($type != 0) { // 不是主线任务
            return false;
        }
        $needStr = $taskDataArr['need'];
        if (empty($needStr)) { // 未定义任务需求
            return false;
        }
        $needArr = json_decode($needStr, true);
        if ($needArr === NULL) { // 任务需求数据解码失败
            return fasle;
        }
        if (!isset($needArr['items']) || count($needArr['items']) === 0) {
            return false;
        }
        return $needArr['items'];
    }
}
?>