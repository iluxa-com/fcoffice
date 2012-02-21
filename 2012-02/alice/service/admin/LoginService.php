<?php
/**
 * 登录服务类(仅用于本地开发用)
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class LoginService extends Service {
    /**
     * 重载父类的构造函数(目的是阻止调用父类的构造函数)
     * @param array $configArr array($service, $action, $index, $lastLogin)
     */
    public function __construct($configArr) {
        list($service, $action, $index, $lastLogin) = $configArr;
        $this->_index = $index;
    }

    /**
     * 登录
     * @param string $email E-mail
     */
    public function login($email = NULL) {
        if (App::get('Platform', false) !== 'Devel') {
            exit('This platform is not supported!');
        }
        // SNS用户帐号
        $dataArr = array(
            'email' => $email,
            'password' => '123456',
        );
        // SNS用户登录
        $tempArr = SNS::login($dataArr);
        // 如果登录失败,退出
        if (!$tempArr) {
            $this->_data['msg'] = 'sns login fail';
            return;
        }
        // 创建用户(用户存在时直接返回用户ID)
        $userId = User::createUser($tempArr['sns_uid']);
        // 如果没取到用户ID,退出
        if (!$userId) {
            $this->_data['msg'] = 'create user fail';
            return;
        }
        $_GET['sns_uid'] = $tempArr['sns_uid'];
        $_GET['sns_session_key'] = $tempArr['sns_session_key'];
        $sns = App::getSNS();
        $snsUserInfo = $sns->getUserInfo();
        // 构造当前用户信息
        $dataArr = array(
            'user_id' => $userId,
            'sns_uid' => $tempArr['sns_uid'],
            'sns_session_key' => $tempArr['sns_session_key'],
            'sns_user_info' => $snsUserInfo,
            'login_time' => CURRENT_TIME,
        );
        // 注册当前用户信息进SESSION
        App::setCurrentUser($dataArr);
        $this->_ret = 0;
    }

    /**
     * 登录(调试用)
     * @param int $userId 用户ID
     * @param string $key 密钥
     */
    public function login2($userId, $key) {
        if (!is_numeric($userId)) {
            $this->_data['msg'] = 'bad user id';
            return;
        }
        if ($key !== 'xC147GskdRK4ne96wniL0quVcVpVa1gB') {
            $this->_data['msg'] = 'invalid key';
            return;
        }
        $userModel = new UserModel($userId);
        if (!$userModel->exists()) {
            $this->_data['msg'] = 'user not exists!';
            return;
        }
        $currentUser = App::getCurrentUser(false);
        if (empty($currentUser)) {
            $this->_data['msg'] = 'game account not login';
            return;
        }
        $currentUser['user_id_old'] = $currentUser['user_id'];
        $currentUser['user_id'] = $userId;
        App::setCurrentUser($currentUser);
        $this->_ret = 0;
    }
}
?>