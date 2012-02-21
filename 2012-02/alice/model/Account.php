<?php
/**
 * 帐号类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class Account {
    /**
     * 重置指定用户的帐号
     * @param int $userId 用户ID
     * @return true/string 成功返回true,失败返回string
     */
    public static function reset($userId) {
        $userModel = new UserModel($userId);
        if (!$userModel->exists()) { // 不存在或已经重置过
            return 'already reset or invalid user_id';
        }
        $snsUid = $userModel->hGet('sns_uid', false);
        if ($snsUid === false) { // 没取到
            return 'cannot get sns uid';
        }
        $relationModel = new RelationModel($snsUid);
        if ($relationModel->set($userId . '=INIT') === false) { // 设置失败
            return 'relation set fail';
        }
        $keyArr = $userModel->RH()->getKeys('*:' . $userId . '*');
        $tempArr = array();
        foreach ($keyArr as $key) {
            $arr = explode(':', $key);
            if (isset($arr[1]) && $arr[1] == $userId) { // 匹配指定用户
                $tempArr[] = $key;
            }
        }
        if (!empty($tempArr) && $userModel->RH()->delete($tempArr) === false) {
            return 'del keys fail';
        }
        self::__clearLog($userId);
        return true;
    }

    /**
     * 清除指定用户的日志
     * @param int $userId 用户ID
     */
    private static function __clearLog($userId) {
        $classArr = array(
            'ChestInviteSQLModel',
            'FriendTaskSQLModel',
            'LeaveMsgSQLModel',
            'NewsLogSQLModel',
            'OtherMsgSQLModel',
            'RequestGiftSQLModel',
            'SendGiftSQLModel',
        );
        foreach ($classArr as $class) {
            $obj = App::getInst($class, $userId, false, $userId);
            $obj->SH()->find(array('user_id' => $userId))->delete();
        }
    }
}
?>