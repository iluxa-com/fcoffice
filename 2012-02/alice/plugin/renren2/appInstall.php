<?php
/**
 * 应用安装(授权、好友邀请等处理)
 * @param bool
 */
function appInstall() {
    $xnSigAdded = isset($_GET['xn_sig_added']) ? $_GET['xn_sig_added'] : '';
    $ref = isset($_GET['ref']) ? $_GET['ref'] : ''; // ref参数为自定义的(详情请参看js中的FH.inviteFriend()方法)
    if ($xnSigAdded === '0') { // 未添加应用
        if ($ref === 'invite_accept' && isset($_GET['sender'])) {
            $_SESSION['invite_sender'] = $_GET['sender']; // 邀请发送人sns uid
        }
        require_once BASE_DIR . "/platform/renren2/install.inc.php";
        exit;
    }
    // IE下面SESSION处理有问题,需要加上P3P头
    header('P3P: CP="NOI DEV PSA PSD IVA PVD OTP OUR OTR IND OTC"');
    return true;
}
?>