<?php
/**
 * 应用安装(授权、好友邀请等处理)
 * @param bool
 */
function appInstall() {
    // IE下面SESSION处理有问题,需要加上P3P头
    header('P3P: CP="NOI DEV PSA PSD IVA PVD OTP OUR OTR IND OTC"');
    return true;
}
?>