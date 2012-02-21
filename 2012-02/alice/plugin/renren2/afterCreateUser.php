<?php
/**
 * 创建帐号后相关处理
 * @param int $userId 用户ID
 * @param bool $isNewUser 是否为新注册用户
 * @return bool
 */
function afterCreateUser($userId, $isNewUser) {
    if (isset($_GET['ref']) && $_GET['ref'] === 'invite_send' && isset($_GET['flag']) && $_GET['flag'] === 'success' && isset($_GET['count'])) {
        Stat::userDayIncr('invite_send', $_GET['count']);
    }
    if ($isNewUser && isset($_SESSION['invite_sender'])) {
        $snsUid2 = $_SESSION['invite_sender'];
        $relationModel = new RelationModel($snsUid2);
        $userId2 = $relationModel->get(); // 用户ID
        if (is_numeric($userId2) && (!isset($_SESSION['invite_flag']) || $_SESSION['invite_flag'] != $userId)) {
            $userModel = new UserModel($userId2);
            if ($userModel->exists() && $userModel->hIncrBy('invite_num', 1) !== false) {
                Stat::userDayIncr('invite_accept');
                $_SESSION['invite_flag'] = $userId;
                if (defined('FESTIVAL_7_7_FLAG') && FESTIVAL_7_7_FLAG == 1) { // 七夕情人节活动
                    Bag::incrItem(7109, 1, $userId2);
                    // 日志数据
                    $contentArr = array(
                        'from_user_id' => $userId,
                        'items' => array(
                            array('id' => 7109, 'num' => 1,),
                        ),
                    );
                } else {
                    $contentArr = array(
                        'from_user_id' => $userId,
                    );
                }
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