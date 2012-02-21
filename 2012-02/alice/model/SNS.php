<?php
/**
 * SNS类(开发测试用,正式环境不需要)
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class SNS {
    /**
     * 访问控制键
     * @var string
     */
    const ACL_KEY = 'SNS_ACL';

    /**
     * 创建用户
     * @param array $dataArr 数据数组
     * @return bool
     */
    public static function createUser($dataArr) {
        $snsUid = self::genUid($dataArr['email']);
        $snsUserModel = new SNSUserModel($snsUid);
        if ($snsUserModel->exists()) { // 用户已存在
            return false;
        }
        $dataArr = array_merge(array('sns_uid' => $snsUid), $dataArr);
        return $snsUserModel->hMset($dataArr);
    }

    /**
     * 登录
     * @param array $dataArr 数据数组(必须包含email和password)
     * @return array/false
     */
    public static function login($dataArr) {
        if (!isset($dataArr['email']) || !isset($dataArr['password'])) {
            return false;
        }
        $snsUid = self::genUid($dataArr['email']);
        $snsUserModel = new SNSUserModel($snsUid);
        if (!$snsUserModel->exists()) { // 用户不存在
            return false;
        }
        $password = $snsUserModel->hGet('password', false);
        if ($password !== false && $password === md5($dataArr['password'])) {
            $tempArr = array(
                'sns_uid' => $snsUid,
                'sns_session_key' => session_id(),
                'login_time' => CURRENT_TIME,
            );
            $_SESSION[self::ACL_KEY] = $tempArr;
            return $tempArr;
        }
        return false;
    }

    /**
     * 生成ID
     * @param string $email E-mail地址
     * @return string
     */
    public static function genUid($email) {
        return sprintf('%032X', crc32($email));
    }
}
?>