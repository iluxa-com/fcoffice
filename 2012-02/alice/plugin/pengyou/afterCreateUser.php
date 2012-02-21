<?php
/**
 * 创建帐号后相关处理
 * @param int $userId 用户ID
 * @param bool $isNewUser 是否为新注册用户
 * @return bool
 */
function afterCreateUser($userId, $isNewUser) {
    if ($isNewUser) {
        $sns = App::getSNS();
        $snsUid2 = $sns->checkInviteKey();
        if ($snsUid2 === false) { // 检验失败,直接返回true
            return true;
        }
        $relationModel = new RelationModel($snsUid2);
        $userId2 = $relationModel->get(); // 用户ID
        if (is_numeric($userId2)) {
            $userModel = new UserModel($userId2);
            if ($userModel->exists() && $userModel->hIncrBy('invite_num', 1) !== false) {
                Stat::userDayIncr('invite_accept');
                $contentArr = array(
                    'from_user_id' => $userId,
                );
                $dataArr = array(
                    'log_type' => 4, // 成功邀请好友
                    'content' => json_encode($contentArr),
                );
                User::log(User::LOG_TYPE_NEWS, $dataArr, $userId2); // 写好友日志(成功邀请好友)
            }
        }
    }
    return true;
}
?>