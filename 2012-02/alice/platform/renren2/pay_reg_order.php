<?php
/**
 * @link http://wiki.dev.renren.com/wiki/%E6%A0%A1%E5%86%85%E8%B1%86%E6%B6%88%E8%B4%B9%E6%8A%80%E6%9C%AF%E6%96%87%E6%A1%A3
 */
require_once '../../config.php';

try {
    // 设置登录用户
    $currentUser = App::getCurrentUser();
    App::set('user_id', $currentUser['user_id']);

    $srcNum = isset($_GET['amount']) ? $_GET['amount'] : '';
    $configArr = array(
        1 => 10,
        10 => 105,
        20 => 220,
        50 => 575,
        100 => 1200,
    );
    if (!isset($configArr[$srcNum])) {
        $dataArr = array(
            'ret' => -1,
            'msg' => 'invalid src_num',
        );
    } else {
        $dstNum = $configArr[$srcNum];
        $dataArr = Pay::regOrder($srcNum, $dstNum);
    }
} catch (Exception $e) {
    $dataArr = array(
        'ret' => $e->getCode(),
        'msg' => 'catch exception',
    );
}
exit(json_encode($dataArr));
?>