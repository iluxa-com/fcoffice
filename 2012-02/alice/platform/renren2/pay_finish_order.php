<?php
/**
 * @link http://wiki.dev.renren.com/wiki/%E6%A0%A1%E5%86%85%E8%B1%86%E6%B6%88%E8%B4%B9%E6%8A%80%E6%9C%AF%E6%96%87%E6%A1%A3
 */
require_once '../../config.php';

// 注销SESSION
session_destroy();

$xnSigUser = isset($_POST['xn_sig_user']) ? $_POST['xn_sig_user'] : '';
$xnSigSkey = isset($_POST['xn_sig_skey']) ? $_POST['xn_sig_skey'] : '';
$xnSigOrderId = isset($_POST['xn_sig_order_id']) ? $_POST['xn_sig_order_id'] : '';
$xnSigSessionKey = isset($_POST['xn_sig_session_key']) ? $_POST['xn_sig_session_key'] : '';

if ($xnSigSkey == md5(base64_decode('MjAxMTEwMTc=') . $xnSigUser)) { // 检查签名
    // 通过平台ID获取用户ID
    $relationModel = new RelationModel($xnSigUser);
    $userId = $relationModel->get();
    if (is_numeric($userId)) {
        // 设置SNS接口所需的参数
        $_GET['xn_sig_user'] = $xnSigUser;
        $_GET['xn_sig_session_key'] = $xnSigSessionKey;

        // 设置登录用户
        App::set('user_id', $userId);

        // 完成订单
        $retArr = Pay::finishOrder($xnSigOrderId);
        if (is_array($retArr)) {
            exit(json_encode($retArr));
        }
    }
}
exit;
?>