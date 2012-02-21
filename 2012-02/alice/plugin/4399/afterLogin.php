<?php
/**
 * 登录后相关处理
 * @param int $userId 用户ID
 * @param string $platform 平台名称(小写)
 * @return bool
 */
function afterLogin($userId, $platform) {
    if (isset($_GET['tag']) && $_GET['tag'] === 'iframe') {
        require_once BASE_DIR . "/platform/4399/index.iframe.php";
        exit;
    }
    return true;
}
?>