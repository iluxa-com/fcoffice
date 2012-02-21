<?php
try {
    // 引入全局配置文件
    require_once 'config.php';
    // 应用安装(授权、好友邀请等处理)
    if (App::callFunc('appInstall') === false) {
        throw new UserException(UserException::ERROR_PLUGIN, 'PLUGIN_APP_INSTALL_FAIL');
    }
    // 获取SNS实例
    $sns = App::getSNS();
    // 获取SESSION中当前用户信息(未取到时不抛出异常)
    $currentUser = App::getCurrentUser(false);
    if ($currentUser && $currentUser['sns_uid'] === $sns->getSNSUid() && $currentUser['sns_session_key'] === $sns->getSessionKey() && isset($currentUser['sns_user_info'])) {
        $userId = $currentUser['user_id'];
    } else {
        // 获取SNS用户信息
        $snsUserInfo = $sns->getUserInfo();
        // 如果获取SNS用户信息失败,抛异常
        if (!$snsUserInfo) {
            throw new UserException(UserException::ERROR_SYSTEM, 'GET_SNS_USER_INFO_FAIL');
        }
        // 安装控制(放量、白名单等处理)
        if (App::callFunc('installControl', array($sns->getSNSUid())) === false) {
            throw new UserException(UserException::ERROR_PLUGIN, 'PLUGIN_INSTALL_CONTROL_FAIL');
        }
        // 创建用户(用户存在时直接返回用户ID)
        $userId = User::createUser($sns->getSNSUid(), $isNewUser);
        // 如果没取到用户ID,退出
        if (!$userId) {
            throw new UserException(UserException::ERROR_SYSTEM, 'CREATE_USER_FAIL');
        }
        // 创建帐号后相关处理
        if (App::callFunc('afterCreateUser', array($userId, $isNewUser)) === false) {
            throw new UserException(UserException::ERROR_PLUGIN, 'PLUGIN_AFTER_CREATE_USER_FAIL');
        }
        // 构造当前用户信息
        $currentUser = array(
            'user_id' => $userId, // 用户ID
            'sns_uid' => $sns->getSNSUid(), // 平台ID
            'sns_session_key' => $sns->getSessionKey(), // 平台Key
            'sns_user_info' => $snsUserInfo, // 平台用户信息
            'login_time' => CURRENT_TIME, // 登录时间
        );
        // 注册当前用户信息进SESSION
        App::setCurrentUser($currentUser);
    }
    // 获取平台设置
    $platform = strtolower(App::get('Platform'));
    //登录后相关处理
    if (App::callFunc('afterLogin', array($userId, $platform)) === false) {
        throw new UserException(UserException::ERROR_PLUGIN, 'PLUGIN_AFTER_LOGIN_FAIL');
    }
    // 加载首页
    require_once BASE_DIR . "/platform/{$platform}/index.inc.php";
} catch (Exception $e) {
    defined('LOG_EXCEPTION') && constant('LOG_EXCEPTION') && Debug::log(Debug::getExceptionMsg($e), Debug::TYPE_INDEX_EXCEPTION + $e->getCode());
    exit('Error(code=' . $e->getCode() . ')');
}
?>