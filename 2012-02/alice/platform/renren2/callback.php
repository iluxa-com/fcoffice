<?php
require_once '../../config.php';

/**
 * 检查签名
 * @link http://wiki.dev.renren.com/wiki/Post-Authorize_URL
 * @link http://wiki.dev.renren.com/wiki/Post-Remove_URL
 * @link http://wiki.dev.renren.com/wiki/%E5%85%B3%E4%BA%8Exn_sig%E5%8F%82%E6%95%B0#xn_sig
 * @link http://wiki.dev.renren.com/wiki/Calculate_signature
 * @link http://wiki.dev.renren.com/wiki/Connect%E7%9B%B8%E5%85%B3%E7%9A%84%E5%BA%94%E7%94%A8%E8%AE%BE%E7%BD%AE#.E5.9B.9E.E8.B0.83.E8.AE.BE.E7.BD.AE
 * @param array $dataArr 数据数组
 * @param bool $onlyXnSigPrefixKey 仅对xn_sig_开头的Key计算签名(应用添加/缷载时为false,好友邀请时为true)
 * @param string $secretKey 安全密钥
 * @return bool
 */
function checkSig($dataArr, $onlyXnSigPrefixKey = false, $secretKey = '6efce65adcfe46118d8fcfb839b7158d') {
    if (!isset($dataArr['xn_sig'])) {
        return false;
    }
    $newDataArr = array();
    foreach ($dataArr as $key => $val) {
        if ($key === 'xn_sig') {
            continue;
        } else if (preg_match('/^xn_sig_/', $key)) {
            $key = substr($key, 7); // 移除"xn_sig_"前缀
        } else if ($onlyXnSigPrefixKey) { // 排除非"xn_sig_"开头的Key
            continue;
        }
        $newDataArr[] = "{$key}={$val}";
    }
    sort($newDataArr);
    $str = implode('', $newDataArr) . $secretKey;
    return (md5($str) === $dataArr['xn_sig']);
}

if (isset($_POST) && !empty($_POST)) {
    if (checkSig($_POST, false)) {
        $xnSigAdded = $_POST['xn_sig_added'] ? $_POST['xn_sig_added'] : '';
        if ($xnSigAdded == 1) { // 添加
            Debug::log($_POST, Debug::TYPE_APP_ADD);
        } else if ($xnSigAdded == 0) { // 移除
            Debug::log($_POST, Debug::TYPE_APP_REMOVE);
        }
    }
}
?>