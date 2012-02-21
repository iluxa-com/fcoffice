<?php
/**
 * 内测奖励处理函数
 * @param int $snsUid 平台ID
 * @param int $userId 用户ID
 * @return bool true表示操作成功,false表示操作失败
 */
function neiceReward($snsUid, $userId) {
    // 内测玩家奖励处理
    $platform = strtolower(App::get('Platform'));
    $filename = BASE_DIR . DS . "platform/{$platform}/neice_id.txt";
    if (!file_exists($filename)) { // 文件不存在
        return true;
    }
    $idStr = file_get_contents($filename);
    $idArr = preg_split('/[\r\n]+/', $idStr, -1, PREG_SPLIT_NO_EMPTY);
    if (!in_array($snsUid, $idArr)) {
        return true;
    }
    $rewardArr = array(
        4001 => 5,
        4002 => 3,
        4003 => 2,
        6001 => 2,
    );
    $bagModel = new BagModel($userId);
    if ($bagModel->hMset($rewardArr) === false) {
        return false;
    }
    $userModel = App::getInst('UserModel', $userId, false, $userId);
    if ($userModel->hSet('has_neice_reward', 1) === false) {
        return false;
    }
    return true;
}
?>