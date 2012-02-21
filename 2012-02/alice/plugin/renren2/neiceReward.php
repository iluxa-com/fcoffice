<?php
/**
 * 内测奖励处理函数
 * @param int $snsUid 平台ID
 * @param int $userId 用户ID
 * @return bool true表示操作成功,false表示操作失败
 */
function neiceReward($snsUid, $userId) {
    // 内测玩家奖励处理
    $neiceModel = new NeiceModel($snsUid); // 注意这里要传平台ID
    $neiceDataArr = $neiceModel->hGetAll();
    if (empty($neiceDataArr)) { // 没取到
        return true;
    }
    if (isset($neiceDataArr['flag'])) { // 已经给过奖励
        return true;
    }
    $bagModel = new BagModel($userId);
    if (isset($neiceDataArr['old'])) { // 老玩家奖励处理
        $rewardArr = array(
            5 => array(7901 => 100, 4002 => 5, 7907 => 5, 3002 => 1,),
            10 => array(7901 => 200, 4002 => 10, 7907 => 10, 3002 => 3,),
            15 => array(7901 => 400, 4002 => 15, 7907 => 20, 3004 => 3,),
            20 => array(7901 => 600, 4002 => 20, 7907 => 30, 3075 => 3,),
            25 => array(7901 => 800, 4003 => 15, 7907 => 40, 3075 => 5,),
            101 => array(7901 => 1000, 4003 => 20, 7907 => 50, 3075 => 8,),
        );
        $grade = $neiceDataArr['old'];
        $itemArr = array();
        foreach ($rewardArr as $needGrade => $val) {
            if ($grade <= $needGrade) {
                $itemArr = $val;
                break;
            }
        }
        foreach ($itemArr as $itemId => $num) {
            if (Bag::incrItem($itemId, $num, $userId) === false) {
                return false;
            }
        }
    }
    if (isset($neiceDataArr['new'])) { // 内测玩家奖励处理
        $rewardArr = array(
            5 => array(7901 => 80, 4002 => 5, 6003 => 1,),
            10 => array(7901 => 150, 4002 => 10, 6006 => 1,),
            15 => array(7901 => 300, 4003 => 7, 6009 => 1, 6012 => 1,),
            101 => array(7901 => 600, 4003 => 10, 6015 => 1,),
        );
        $grade = $neiceDataArr['new'];
        $itemArr = array();
        foreach ($rewardArr as $needGrade => $val) {
            if ($grade <= $needGrade) {
                $itemArr = $val;
                break;
            }
        }
        foreach ($itemArr as $itemId => $num) {
            if (Bag::incrItem($itemId, $num, $userId) === false) {
                return false;
            }
        }
    }
    if ($neiceModel->hIncrBy('flag', 1) === false) {
        return false;
    }
    return true;
}
?>